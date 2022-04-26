<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseLog;
use App\Models\PurchaseItem;
use App\Models\PurchaseCredit;
use App\Models\PurchasePayment;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseLogController extends Controller
{
    public function save(Request $request)
    {

        foreach ($request->items as $item) {
            if ($item['purchase_qty'] == null) {
                return 'items_error';
            }
        }

        if ($request->items == []) {
            return "items_array_error";
        }

        $request->validate([

            'purchase_invoice_no' => 'required',
            'purchase_date' => 'required',
            'vendor_name'=>'max:10|unique:vendors,mobile',
            'bill_type' => 'required'

        ]);

        DB::beginTransaction();
        try{


        if ($request->id) {
            $purchase_log = PurchaseLog::FindOrFail($request->id);

            $purchase_items = PurchaseItem::where('purchase_id', $purchase_log->id)->get();

            foreach ($purchase_items as $purchase_item) {

                //updating quantity under product table

                $product = Product::FindOrFail($purchase_item->product_id);
                $product->quantity = $product->quantity - $purchase_item->purchase_qty;
                $product->save();


                //deleting purchase item one by one
                $purchase_item->delete();
            }

            if ($purchase_log->bill_type == 1) {
                $purchase_credit = PurchaseCredit::where('purchase_id', $purchase_log->id)->first();
                if ($purchase_credit)
                    $purchase_credit->delete();
            } else {
                //deleting payments entry in ustomer_rewards table
                $payment = PurchasePayment::where('purchase_id', $purchase_log->id)->first();
                if ($payment)
                    $payment->delete();
            }

            $purchase_log_id = $request->id;
        } else {
            $purchase_log = new PurchaseLog;

            $statement = DB::select("SHOW TABLE STATUS LIKE 'purchase_logs'");
            $purchase_log_id = $statement[0]->Auto_increment;
        }



        $purchase_log->user_id = Auth::user()->id;
        if ($request->vendor_name) {
            $vendor = new Vendor;

            $vendor->user_id = Auth::user()->id;
            $vendor->name = strtoupper($request->vendor_name);
            $vendor->mobile = $request->vendor_mobile;
            $vendor->save();
            $purchase_log->vendor_id = $vendor->id;
        }
        if ($request->vendor_id)
            $purchase_log->vendor_id = $request->vendor_id;

        if ($request->bill_type)
            $purchase_log->bill_type = $request->bill_type;
        $purchase_log->purchase_invoice_no = $request->purchase_invoice_no;
        $purchase_log->purchase_date = $request->purchase_date;
        $purchase_log->remarks = $request->remarks;
        /*********************************/ //////
        if ($request->pay_type)
            $purchase_log->pay_type = $request->pay_type;


        /*********************************/ //////
        //finding total products and purchase quantities
        $total_products = 0;
        $total_qty = 0;
        $total_amount = 0;
        $total_tax = 0;
        $total_taxable_amount = 0;

        foreach ($request->items as $item) {


            $purchase_item = new PurchaseItem;

            $purchase_item->purchase_id = $purchase_log_id;
            if (isset($item['product_id'])) {
                $purchase_item->product_id = $item['product_id'];
                $product = Product::where('id', $item['product_id'])->whereNull('gst')->first();
                if($product)
                $product->gst =  $item['gst_percentage'];
            }
            else
            {
                $product = new Product;

                $product->name = strtoupper($item['product_name']);
                $product->category_id = 1;
                $product->user_id = Auth::user()->id;
                $product->quantity = 0;
                $product->opening_quantity = 0;
                $product->gst =  $item['gst_percentage'];
                $product->save();
                $purchase_item->product_id = $product->id;

            }


            $purchase_item->purchase_qty = $item['purchase_qty'];
            $purchase_item->rate_per_qty = $item['rate_per_qty'];
            $purchase_item->gst_percentage = $item['gst_percentage'];
            $purchase_item->taxable_amount = $item['taxable_amount'];
            $purchase_item->tax = $item['tax'];
            $purchase_item->amount = $item['amount'];
            $purchase_item->created_at = $request->purchase_date;

            //Product table quantity updation
            $product = Product::FindOrFail($purchase_item->product_id);
            $product->quantity = $product->quantity + $purchase_item->purchase_qty;
            $product->save();


            //addition to find total products and purchase quantities
            $total_products = $total_products + 1;
            $total_qty = $total_qty + $purchase_item->purchase_qty;
            if ($purchase_item->tax)
                $total_tax = $total_tax + $purchase_item->tax;
            if ($purchase_item->amount)
                $total_amount = $total_amount + $purchase_item->amount;
            if ($purchase_item->taxable_amount)
                $total_taxable_amount = $total_taxable_amount + $purchase_item->taxable_amount;
            $purchase_item->save();
        }

        $purchase_log->total_products = $total_products;
        $purchase_log->total_qty = $total_qty;
        $purchase_log->total_taxable_amount = $total_taxable_amount;
        $purchase_log->total_tax = $total_tax;
        $purchase_log->total_amount = $total_amount;

        //updating purchase_credits table if bill type is CREDIT
        if ($total_amount > 0) {
            if ($request->bill_type == 1) {

                $purchase_credit = new PurchaseCredit;
                $purchase_credit->user_id = Auth::user()->id;

                if ($request->vendor_id) {
                    $purchase_credit->vendor_id = $request->vendor_id;
                } else {

                    $purchase_credit->vendor_id = $vendor->id;
                }

                $purchase_credit->purchase_id = $purchase_log_id;
                $purchase_credit->amount = $purchase_log->total_amount;
                $purchase_credit->remarks = "CREDIT PURCHASE";
                $purchase_credit->created_at = $request->purchase_date;
                $purchase_credit->save();
            } else {

                $payment = new PurchasePayment;
                if ($request->date)
                    $payment->payment_date = $request->purchase_date;
                else
                    $payment->payment_date = date('Y-m-d');

                $payment->user_id = Auth::user()->id;


                if ($request->vendor_id) {
                    $payment->vendor_id = $request->vendor_id;
                } else {

                    $payment->vendor_id =  $vendor->id;
                }



                $payment->purchase_id = $purchase_log_id;
                if ($request->pay_type)
                    $payment->pay_type = $request->pay_type;
                else
                    $payment->pay_type = "Cash";

                $payment->amount = $purchase_log->total_amount;

                $payment->save();
            }
        }



        $purchase_log->save();

        DB::commit();
        return 'success';


        } catch(\Exception $e)
        {
            DB::rollBack();
            return 'failed';
        }
    }





    public function getPurchases(Request $request)
    {

        $purchaselogs =  Purchaselog::with('purchaseItems');

        if ($request->from_date) {

            $purchaselogs->where('purchase_date', '>=', $request->from_date);
        }
        if ($request->to_date) {

            $purchaselogs->where('purchase_date', '<=', $request->to_date);
        }
        if (!$request->from_date && !$request->to_date) {
            $purchaselogs->where('purchase_date', '=', Carbon::today());
        }

        return $purchaselogs->orderBy('purchase_date', 'desc')->get();
    }

    public function getTodayPurchases(Request $request)
    {
        $today_purchase =  PurchaseLog::with('purchaseItems')->whereDate('created_at', '=', Carbon::today())->orderBy('id', 'desc')->get();


        return $today_purchase;
    }

    public function deletePurchase(Request $request)
    {

        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $purchase_log = PurchaseLog::FindOrFail($request->id);
            $purchase_items = PurchaseItem::where('purchase_id', $purchase_log->id)->get();

            foreach ($purchase_items as $purchase_item) {

                //updating quantity under product table

                $product = Product::FindOrFail($purchase_item->product_id);
                $product->quantity = $product->quantity - $purchase_item->purchase_qty;
                $product->save();


                //deleting purchase item one by one
                $purchase_item->delete();
            }

            if ($purchase_log->bill_type == 1) {
                $purchase_credit = PurchaseCredit::where('purchase_id', $purchase_log->id)->first();
                if ($purchase_credit)
                    $purchase_credit->delete();
            } else {
                //deleting payments entry in ustomer_rewards table
                $payment = PurchasePayment::where('purchase_id', $purchase_log->id)->first();
                if ($payment)
                    $payment->delete();
            }

            //deleting purchase entry
            $purchase_log->delete();
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return 'failed';
        }
    }
}

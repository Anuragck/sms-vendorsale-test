<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Product;
use App\Models\Company;
use App\Models\CustomerReward;
use App\Models\CustomerCredit;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SaleController extends Controller
{
    public function save(Request $request)
    {
        // return $request;
        if(!$request->customer_id)

        $request->validate([
            'name'=>'unique:customers,name',
            'bill_type' => 'required',
            'mobile'=>'min:10|max:10|unique:customers,mobile',

        ]);
        else
        $request->validate([

            'bill_type' => 'required',


        ]);

        DB::beginTransaction();
        try {
            if ($request->id) {

                $sale = Sale::FindOrFail($request->id);
                $invoice_no = $sale->invoice_no;
                $saleitems = SaleItem::where('sale_id', $sale->invoice_no)->get();
                if ($saleitems) {
                    foreach ($saleitems as $saleitem) {

                        //updating quantity under product table
                        if ($saleitem->category_id == 1) {
                            $product = Product::FindOrFail($saleitem->product_id);
                            if($product)
                            {
                                $product->quantity = $product->quantity + $saleitem->quantity;
                                $product->save();
                            }
                            else
                            return "Error";

                        }

                        //deleting saleitem one by one
                        $saleitem->delete();
                    }
                }

                //deleting reward points entry in ustomer_rewards table
                $customer_reward = CustomerReward::where('invoice_no', $sale->invoice_no)->first();
                if ($customer_reward)
                $customer_reward->delete();

                //deleting reward points entry in ustomer_rewards table
                $payment = Payment::where('invoice_no', $sale->invoice_no)->first();
                if ($payment)
                $payment->delete();

                //deleting from Credit Table
                if ($sale->bill_type == 1) {
                    $customer_credit = CustomerCredit::where('invoice_no', $sale->invoice_no)->first();
                    if ($customer_credit)
                    $customer_credit->delete();
                }
            } else {
                $sale = new Sale;
            }


            if (!$request->id) {
                $last_record = Sale::withTrashed()->get();

                $last_record_id = Sale::withTrashed()->orderBy('id', 'DESC')->first();

                if ($last_record->isEmpty())
                $invoice_no = 1;
                else
                $invoice_no = $last_record_id->id + 1;
            }


            $sale->invoice_no = $invoice_no;
            $company = Company::where('id',1)->first();

            if($company->invoice_prefix)
                $sale->invoice_prefix = $company->invoice_prefix;
            $sale->user_id = Auth::user()->id;

            //adding bill type (1-CREDIT or 2-CASH)
            $sale->bill_type = $request->bill_type;


            //adding type(1- Customer 2-Vendor)
            $sale->type = 1;

            if($request->customer_id)
            $sale->customer_id = $request->customer_id;
            else
            {
                $new_customer = new Customer();
                $new_customer->user_id = Auth::user()->id;
                $new_customer->name = strtoupper($request->name);
                $new_customer->mobile = $request->mobile;
                $new_customer->gst_no = $request->gst_no;
                $new_customer->save();
                $sale->customer_id = $new_customer->id;

            }


            $sale->gst_bill = $request->gst_bill;
            if($request->wholesale)
                $sale->wholesale = $request->wholesale;


            if ($request->vehicle)
            $sale->vehicle = $request->vehicle;


            if ($request->register_no) {
                //To make register_no clean ie KL-11-A-134 / KL 11 A 134 / KL-11 A 134 becomes KL11A134
                $register_no = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($request->register_no));
                $sale->register_no = $register_no;
            }



            //If date is selected or current date
            if ($request->date)
            $sale->invoice_date = $request->date;
            else
            $sale->invoice_date = date('Y-m-d');

            $sale->invoice_year = Carbon::parse($sale->invoice_date)->format('Y');
            $total_amount = 0;
            $total_discount = 0;
            $total_gst = 0;
            $total_taxable_amount=0;
            $check=0;

            $user_id = Auth::user()->id;
            foreach ($request->items as $item) {

                $saleitem = new SaleItem;
                if ($request->date)
                $saleitem->created_at = $request->date;
                $saleitem->user_id = $user_id;
                $saleitem->sale_id = $invoice_no;
                $saleitem->category_id = $item['category_id'];
                if($saleitem->category_id==1)
                {
                    $check =1;
                }
                $saleitem->product_id = $item['product_id'];
                $saleitem->quantity = $item['quantity'];
                $saleitem->rate = $item['rate'];
                $saleitem->total=$saleitem->quantity * $saleitem->rate;

                //Product table quantity updation (only sale category)
                if ($saleitem->category_id == 1) {
                    $product = Product::FindOrFail($saleitem->product_id);
                    $product->quantity = $product->quantity - $saleitem->quantity;
                    $product->save();
                }

                if($request->gst_bill==0)
                {
                    //finding total_rate by multiplying quantity and rate
                    $saleitem->total_rate =  $saleitem->total;
                    $saleitem->discount = $item['discount'];
                    $total_discount =  $total_discount + $saleitem->discount;

                }
                else{
                    $saleitem->discount = $item['discount'];
                    $saleitem->gross_amount = $saleitem->total - $saleitem->discount;
                    $saleitem->gst = $item['gst'];
                    $gst_tax=$saleitem->gross_amount-$saleitem->gross_amount*100/(100+$saleitem->gst);
                    $saleitem->taxable_amount=$saleitem->gross_amount-$gst_tax;
                    $saleitem->cgst= $gst_tax/2.0;
                    $saleitem->sgst= $gst_tax/2.0;
                    $saleitem->total_rate =  $saleitem->taxable_amount+$gst_tax;
                    $total_discount =  $total_discount + $saleitem->discount;
                    $total_gst = $total_gst+$gst_tax;
                    $total_taxable_amount= $total_taxable_amount+ $saleitem->taxable_amount;
                }


                //finding total amount by adding total_rate of each item
                if($request->gst_bill==0)
                {
                    $total_amount = $total_amount + $saleitem->total_rate;
                }
                else
                {
                    $total_amount = $total_amount + $saleitem->total;
                }


                $saleitem->save();
            }

            $sale->total_amount =  $total_amount;
            $sale->total_discount = $request->total_discount;
            $sale->roundoff = $request->roundoff;
            $sale->total_taxable_amount=$total_taxable_amount;
            $sale->total_cgst=$total_gst/2.0;
            $sale->total_sgst=$total_gst/2.0;

            //finding payable amount
            $sale->pay_amount = $sale->total_amount - $sale->total_discount-$sale->roundoff;

            //finding reward points
            if($check==1)
            $reward_points = $sale->pay_amount * 0.0025;
            else
            $reward_points = $sale->pay_amount * 0.01;




            $sale->reward_points = $reward_points;




            //updating customer_credits table if bill type is CREDIT
            if ($request->bill_type == 1) {
                $customer_credit = new CustomerCredit;
                $customer_credit->user_id = Auth::user()->id;

                if($request->customer_id)
                $customer_credit->customer_id = $request->customer_id;
                else
                {
                    $customer_credit->customer_id =  $new_customer->id;

                }


                $customer_credit->invoice_no = $invoice_no;
                $customer_credit->amount = $sale->pay_amount;
                $customer_credit->remarks = "CREDIT SALE";
                if ($request->date)
                $customer_credit->created_at = $request->date;
                $customer_credit->save();

                $sale->reward_points = 0;


            }
            else{

                $payment = new Payment;
                if ($request->date)
                $payment->payment_date = $request->date;
                else
                $payment->payment_date = date('Y-m-d');

                $payment->credit = 0;
                $payment->user_id = Auth::user()->id;
                $payment->invoice_no = $invoice_no;



                if($request->customer_id)
                $payment->customer_id = $request->customer_id;
                else
                {
                    $payment->customer_id =  $new_customer->id;

                }

                $payment->amount = $sale->pay_amount;
                if($request->pay_type)
                {

                    $payment->pay_type=$request->pay_type;
                    $sale->pay_type=$request->pay_type;
                }
                else
                {
                    $payment->pay_type="Cash";
                    $sale->pay_type="Cash";
                }

                $payment->save();


                //updating reward points in customer_rewards table
                $customer_reward = new CustomerReward;
                $customer_reward->user_id = Auth::user()->id;

                if($request->customer_id)
                $customer_reward->customer_id = $request->customer_id;
                else
                {
                    $customer_reward->customer_id =  $new_customer->id;

                }


                $customer_reward->type = "CREDIT";
                $customer_reward->points = $reward_points;
                $customer_reward->invoice_no = $invoice_no;
                $customer_reward->remarks = "SALE REWARDS";
                if ($request->date)
                $customer_reward->created_at = $request->date;
                $customer_reward->save();

                $sale->reward_points = $reward_points;



            }
            $sale->save();


            DB::commit();


            if($saleitem->save() && $sale->save()){
                // return ["invoice_no"=>$invoice_no,"date"=>$sale->invoice_date];
                return 'success';
            }


        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return 'Failed';
        }


    }


    public function deleteSale(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try{

            $sale = Sale::FindOrFail($request->id);
            $saleitems = SaleItem::where('sale_id', $sale->invoice_no)->get();

            foreach ($saleitems as $saleitem) {

                //updating quantity under product table
                if ($saleitem->category_id == 1) {
                    $product = Product::FindOrFail($saleitem->product_id);
                    if($product)
                    {
                        $product->quantity = $product->quantity + $saleitem->quantity;
                        $product->save();
                    }
                    else
                    return "Error";

                }

                //deleting saleitem one by one
                $saleitem->delete();
            }

            //deleting reward points entry in ustomer_rewards table
            $customer_reward = CustomerReward::where('invoice_no', $sale->invoice_no)->first();
            if($customer_reward )
            $customer_reward->delete();

            //deleting from Credit Table
            if ($sale->bill_type == 1) {
                $customer_credit = CustomerCredit::where('invoice_no', $sale->invoice_no)->first();
                if($customer_credit)
                $customer_credit->delete();
            }
            else
            {
                //deleting payments entry in ustomer_rewards table
                $payment = Payment::where('invoice_no', $sale->invoice_no)->first();
                if ($payment)
                $payment->delete();
            }

            //deleting sale entry
            $sale->delete();

            DB::commit();
            return 'success';


        } catch(\Exception $e) {
            DB::rollBack();
            return 'Failed';
        }
    }


    public function getSales(Request $request)
    {
            $sales =  Sale::with('items', 'customer','items.product');

           if ($request->from_date) {

                $sales->where('invoice_date', '>=', $request->from_date);
            }
            if ($request->to_date) {

                $sales->where('invoice_date', '<=', $request->to_date);
            }
            if(!$request->from_date && !$request->to_date)
            {
                $sales->where('invoice_date', '=',Carbon::today());
            }

            return $sales->orderBy('id', 'desc')->get();

    }

    public function getSaleByInvoice(Request $request)
    {
        $sale =  Sale::with('items', 'customer','items.product','items.category')->where('invoice_no',$request->invoice_no)->first();


        $sale_returns= SaleReturn::with('items', 'customer','items.product','items.category')->where('invoice_no',$request->invoice_no)->get();


        $details[] = [

            'sale'=>$sale,
            'sale_details'  => $sale,
            'sale_return_count'=>$sale_returns->Count(),
            'sale_return_details'=>$sale_returns

        ];
        return $details;


    }

    public function getTodaySales(Request $request)
    {
        $sales =  Sale::with('items', 'customer')->whereDate('created_at', '=', Carbon::today())->orderBy('id', 'desc')->get();


        return $sales;
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class QuotationController extends Controller
{
    public function save(Request $request)
    {

        if(!$request->customer_id)

        $request->validate([
            'name'=>'unique:customers,name',
            'mobile'=>'min:10|max:10|unique:customers,mobile',

        ]);


        // DB::beginTransaction();
        // try {
            if ($request->id) {

                $quotation = Quotation::FindOrFail($request->id);
                $quotation_no = $quotation->quotation_no;
                QuotationItem::where('quotation_id', $quotation_no)->delete();

            } else {
                $quotation = new Quotation;
            }


            if (!$request->id) {
                $last_record = Quotation::withTrashed()->get();
                $last_record_id = Quotation::withTrashed()->orderBy('id', 'DESC')->first();
                if ($last_record->isEmpty())
                $quotation_no = 1;
                else
                $quotation_no = $last_record_id->id + 1;
            }

            $quotation->quotation_no = $quotation_no;

            $quotation->user_id = Auth::user()->id;


            if($request->customer_id)
            $quotation->customer_id = $request->customer_id;
            else
            {
                $new_customer = new Customer();
                $new_customer->user_id = Auth::user()->id;
                $new_customer->name = strtoupper($request->name);
                $new_customer->mobile = $request->mobile;
                $new_customer->gst_no = $request->gst_no;
                $new_customer->save();
                $quotation->customer_id = $new_customer->id;

            }
            $quotation->gst_bill = $request->gst_bill;

            //If date is selected or current date
            if ($request->quotation_date)
            $quotation->quotation_date = $request->quotation_date;
            else
            $quotation->quotation_date = date('Y-m-d');

            $quotation_end_date=Carbon::parse($quotation->quotation_date)->addDays(30);
            $quotation->quotation_end_date=$quotation_end_date;

            $total_amount = 0;
            $total_discount = 0;
            $total_gst = 0;
            $total_taxable_amount=0;
            $check=0;

            $user_id = Auth::user()->id;
            foreach ($request->items as $item) {

                $quotation_item = new QuotationItem;
                if ($request->date)
                $quotation_item->created_at = $request->date;
                $quotation_item->user_id = $user_id;
                $quotation_item->quotation_id = $quotation_no;
                $quotation_item->category_id = $item['category_id'];
                if($quotation_item->category_id==1)
                {
                    $check =1;
                }
                if (isset($item['product_id'])) {
                    $quotation_item->product_id = $item['product_id'];
                }
                else
                {
                    $product = new Product;

                    $product->name = strtoupper($item['product_name']);
                    $product->category_id = $item['category_id'];
                    $product->user_id = Auth::user()->id;
                    $product->quantity = 0;
                    $product->opening_quantity = 0;
                    $product->save();
                    $quotation_item->product_id = $product->id;

                }

                $quotation_item->quantity = $item['quantity'];
                $quotation_item->rate = $item['rate'];
                $quotation_item->total=$quotation_item->quantity * $quotation_item->rate;


                if($request->gst_bill==0)
                {
                    //finding total_rate by multiplying quantity and rate
                    $quotation_item->total_rate =  $quotation_item->total;
                    $quotation_item->discount = $item['discount'];
                    $total_discount =  $total_discount + $quotation_item->discount;

                }
                else{
                    $quotation_item->discount = $item['discount'];
                    $quotation_item->gross_amount = $quotation_item->total - $quotation_item->discount;
                    $quotation_item->gst = $item['gst'];
                    $gst_tax=$quotation_item->gross_amount-$quotation_item->gross_amount*100/(100+$quotation_item->gst);
                    $quotation_item->taxable_amount=$quotation_item->gross_amount-$gst_tax;
                    $quotation_item->cgst= $gst_tax/2.0;
                    $quotation_item->sgst= $gst_tax/2.0;
                    $quotation_item->total_rate =  $quotation_item->taxable_amount+$gst_tax;
                    $total_discount =  $total_discount + $quotation_item->discount;
                    $total_gst = $total_gst+$gst_tax;
                    $total_taxable_amount= $total_taxable_amount+ $quotation_item->taxable_amount;
                }


                //finding total amount by adding total_rate of each item
                if($request->gst_bill==0)
                {
                    $total_amount = $total_amount + $quotation_item->total_rate;
                }
                else
                {
                    $total_amount = $total_amount + $quotation_item->total;
                }


                $quotation_item->save();
            }

            $quotation->total_amount =  $total_amount;
            $quotation->total_discount = $request->total_discount;
            $quotation->roundoff = $request->roundoff;
            $quotation->total_taxable_amount=$total_taxable_amount;
            $quotation->total_cgst=$total_gst/2.0;
            $quotation->total_sgst=$total_gst/2.0;

            //finding payable amount
            $quotation->pay_amount = $quotation->total_amount - $quotation->total_discount-$quotation->roundoff;


            $quotation->save();


           // DB::commit();
           return 'success';



        // }
        // catch(\Exception $e)
        // {
        //     DB::rollBack();
        //     return 'Failed';
        // }


    }


    public function deleteQuotation(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try{

            $quotation = Quotation::FindOrFail($request->id);
            QuotationItem::where('quotation_id', $quotation->quotation_no)->delete();
            $quotation->delete();

            DB::commit();

            return 'success';


        } catch(\Exception $e) {
            DB::rollBack();
            return 'Failed';
        }
    }


    public function getQuotations(Request $request)
    {
            $quotations =  Quotation::with('items', 'customer','items.product');

           if ($request->from_date) {

                $quotations->where('quotation_date', '>=', $request->from_date);
            }
            if ($request->to_date) {

                $quotations->where('quotation_date', '<=', $request->to_date);
            }
            if(!$request->from_date && !$request->to_date)
            {
                $quotations->where('quotation_date', '=',Carbon::today());
            }

            return $quotations->orderBy('id', 'desc')->get();

    }


}


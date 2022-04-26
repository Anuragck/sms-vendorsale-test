<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Product;
use App\Models\CustomerReward;
use App\Models\CustomerCredit;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SaleReturnController extends Controller
{
    public function save(Request $request)
    {
        
        DB::beginTransaction();
        try {
            
            if ($request->id) {
                
                $sale_return = SaleReturn::FindOrFail($request->id);
                $sale_return_items = SaleReturnItem::where('sale_return_id', $sale_return->id)->get();
                
                foreach ($sale_return_items as $sale_return_item) {
                    
                    //updating quantity under product table
                    if ($sale_return_item->category_id == 1) {
                        $product = Product::FindOrFail($sale_return_item->product_id);
                        if($product)
                        {
                            $product->quantity = $product->quantity - $sale_return_item->quantity;
                            $product->save();
                        }
                        else
                        return "Error";
                        
                    }
                    
                    //deleting saleitem one by one
                    $sale_return_item->delete();
                }
                
                //deleting reward points entry in ustomer_rewards table
                $customer_reward = CustomerReward::where('invoice_no', 'SR-'.+$sale_return->invoice_no)->first();
                if($customer_reward )
                $customer_reward->delete();
                
                //deleting from Credit Table
                if ($sale_return->bill_type == 1) {
                    $customer_credit = CustomerCredit::where('invoice_no', 'SR-'.+$sale_return->invoice_no)->first();
                    if($customer_credit)
                    $customer_credit->delete();
                }
                
            } 
            else 
            {
                $sale_return = new SaleReturn;
            }
            
            
            if (!$request->id) {
                $last_record = SaleReturn::withTrashed()->get();
                
                $last_record_id = SaleReturn::withTrashed()->orderBy('id', 'DESC')->first();
                
                if ($last_record->isEmpty())
                $return_invoice_no = 1;
                else
                $return_invoice_no = $last_record_id->id + 1;
            }
            
            
            $sale_return->user_id = Auth::user()->id;
            
            //adding bill type (1-CREDIT or 2-CASH)
            $sale_return->bill_type = $request->bill_type;
            
            $sale_return->customer_id = $request->customer_id;
            
            $sale_return->gst_bill = $request->gst_bill;
            $sale_return->invoice_no=$request->sale_invoice_no;
            
            
            //If date is selected or current date
            if ($request->return_date)
            $sale_return->return_date = $request->return_date;
            else
            $sale_return->return_date = date('Y-m-d');
            
            $total_amount = 0;
            $total_discount = 0;
            $total_gst = 0;
            $total_taxable_amount=0;
            $check=0;
            
            $user_id = Auth::user()->id;
            
            
            
            
            foreach ($request->items as $item) {
                if(isset($item['return_qty']))
                {
                    $sale_return_item = new SaleReturnItem;
                    if ($request->return_date)
                    $sale_return_item->created_at = $request->return_date;
                    $sale_return_item->user_id = $user_id;
                    $sale_return_item->sale_return_id = $return_invoice_no;
                    $sale_return_item->category_id = $item['category_id'];
                    if($sale_return_item->category_id==1)
                    {
                        $check =1;
                    }
                    $sale_return_item->product_id = $item['product_id'];
                    $sale_return_item->return_qty = $item['return_qty'];
                    $sale_return_item->rate = $item['rate'];
                    $sale_return_item->total=$sale_return_item->return_qty * $sale_return_item->rate;
                    
                    //Product table quantity updation (only sale category)
                    if ($sale_return_item->category_id == 1) {
                        $product = Product::FindOrFail($sale_return_item->product_id);
                        $product->quantity = $product->quantity + $sale_return_item->return_qty;
                        $product->save();
                    }
                    
                    if($request->gst_bill==0)
                    {
                        //finding total_rate by multiplying quantity and rate
                        $sale_return_item->total_rate =  $sale_return_item->total;
                        
                        
                    }
                    else{
                        $sale_return_item->discount = $item['discount'];
                        $sale_return_item->gross_amount = $sale_return_item->total - $sale_return_item->discount;
                        $sale_return_item->gst = $item['gst'];
                        $gst_tax=$sale_return_item->gross_amount-$sale_return_item->gross_amount*100/(100+$sale_return_item->gst);
                        $sale_return_item->taxable_amount=$sale_return_item->gross_amount-$gst_tax;
                        $sale_return_item->cgst= $gst_tax/2.0;
                        $sale_return_item->sgst= $gst_tax/2.0;
                        $sale_return_item->total_rate =  $sale_return_item->taxable_amount+$gst_tax;
                        $total_discount =  $total_discount + $sale_return_item->discount;
                        $total_gst = $total_gst+$gst_tax;
                        $total_taxable_amount= $total_taxable_amount+ $sale_return_item->taxable_amount;
                    }
                    
                    
                    //finding total amount by adding total_rate of each item
                    if($request->gst_bill==0)
                    {
                        $total_amount = $total_amount + $sale_return_item->total_rate;
                    }
                    else
                    {
                        $total_amount = $total_amount + $sale_return_item->total;
                    }
                    
                    
                    $sale_return_item->save();
                }
                
            }
            
            $sale_return->total_amount =  $total_amount;
            if($request->gst_bill==0){
                $sale_return->total_discount = $request->total_discount;
            }
            
            else
            {
                $sale_return->total_discount=$total_discount;
            }
            
            $sale_return->total_taxable_amount=$total_taxable_amount;
            $sale_return->total_cgst=$total_gst/2.0;
            $sale_return->total_sgst=$total_gst/2.0;
            
            //finding payable amount
            $sale_return->pay_amount = $sale_return->total_amount - $sale_return->total_discount;
            
            //finding reward points
            if($check==1)
            $reward_points = $sale_return->pay_amount * 0.0025;
            else
            $reward_points = $sale_return->pay_amount * 0.01;
            
            $sale_return->reward_points = -$reward_points;
            
            
            
            
            //updating customer_credits table if bill type is CREDIT
            if ($request->bill_type == 1) {
                $customer_credit = new CustomerCredit;
                $customer_credit->user_id = Auth::user()->id;
                
                $customer_credit->customer_id = $request->customer_id;
                
                
                $customer_credit->invoice_no = 'SR-'.+$return_invoice_no;
                $customer_credit->amount = -$sale_return->pay_amount;
                $customer_credit->remarks = "SALE RETURN";
                if ($request->date)
                $customer_credit->created_at = $request->return_date;
                $customer_credit->save();
            }
            else
            {
                //updating reward points in customer_rewards table
                $customer_reward = new CustomerReward;
                $customer_reward->user_id = Auth::user()->id;
                
                $customer_reward->customer_id = $request->customer_id;
                $customer_reward->type = "DEBIT";
                $customer_reward->points = -$reward_points;
                $customer_reward->invoice_no = 'SR-'.+$return_invoice_no;
                $customer_reward->remarks = "SALE RETURN";
                if ($request->return_date)
                $customer_reward->created_at = $request->return_date;
                $customer_reward->save();
            }
            
            $sale_return->save();
            DB::commit();
            
            return 'success';
            
        } 
        catch(\Exception $e) 
        {
            DB::rollBack();
            return 'Failed';
        }
        
    }
    
    
    public function deleteSaleReturn(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        
        DB::beginTransaction();
        try{
            
            $sale_return = SaleReturn::FindOrFail($request->id);
            $sale_return_items = SaleReturnItem::where('sale_return_id', $sale_return->id)->get();
            
            foreach ($sale_return_items as $sale_return_item) {
                
                //updating quantity under product table
                if ($sale_return_item->category_id == 1) {
                    $product = Product::FindOrFail($sale_return_item->product_id);
                    if($product)
                    {
                        $product->quantity = $product->quantity - $sale_return_item->return_qty;
                        $product->save();
                    }
                    else
                    return "Error";
                    
                }
                
                //deleting saleitem one by one
                $sale_return_item->delete();
            }
            
            //deleting reward points entry in ustomer_rewards table
            $customer_reward = CustomerReward::where('invoice_no', 'SR-'.+$sale_return->invoice_no)->first();
            if($customer_reward )
            $customer_reward->delete();
            
            //deleting from Credit Table
            if ($sale_return->bill_type == 1) {
                $customer_credit = CustomerCredit::where('invoice_no', 'SR-'.+$sale_return->invoice_no)->first();
                if($customer_credit)
                $customer_credit->delete();
            }
            
            
            //deleting sale entry
            $sale_return->delete();
            DB::commit();
            return 'success';
            
            
        } catch(\Exception $e) {
            DB::rollBack();
            return 'Failed';
        } 
    }
    
    
    
    public function  getSaleReturns(Request $request)
    {
        $sale_returns =  SaleReturn::with('items.product.category', 'customer');
        
        
        if ($request->from_date) {
            
            $sale_returns->where('return_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            
            $sale_returns->where('return_date', '<=', $request->to_date);
        }
        if(!$request->from_date && !$request->to_date)
        {
            $sale_returns->where('return_date', '=',Carbon::today());
        }
        
        return $sale_returns->orderBy('id', 'desc')->get();
        
    }
    
    public function getSaleReturnByInvoice(Request $request)
    {
        $sale_return =  SaleReturn::with('items', 'customer','items.product')->where('invoice_no',$request->invoice_no)->first();
        
        return $sale_return;
        
    }
    
    
}
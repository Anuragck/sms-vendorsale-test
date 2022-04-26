<?php

namespace App\Http\Controllers;

use App\Models\Jobcard;
use App\Models\JobcardItem;
use App\Models\JobcardPayment;
use App\Models\Product;
use App\Models\CustomerReward;
use App\Models\CustomerCredit;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobcardController extends Controller
{
    public function save(Request $request)
    {


        if(!$request->customer_id)
        $request->validate([

            'mobile' => 'max:10|min:10|unique:customers,mobile',
            'name' => 'unique:customers,name',
            'jobadvisor_id' => 'required',
            'register_no' => 'required'
        ]);

        else
        $request->validate([
            'jobadvisor_id' => 'required',
            'register_no' => 'required'
        ]);

        DB::beginTransaction();
        try{


            if ($request->id) {

                $jobcard = Jobcard::FindOrFail($request->id);
                $jobitems = JobcardItem::where('jobcard_id', $jobcard->id)->get();
                if ($jobitems) {
                    foreach ($jobitems as $jobitem) {

                        //updating quantity under product table
                        if ($jobitem->category_id == 1) {
                            $product = Product::FindOrFail($jobitem->product_id);
                            if ($product) {
                                $product->quantity = $product->quantity + $jobitem->quantity;
                                $product->save();
                            } else
                            return "Error";
                        }

                        //deleting jobitem one by one
                        $jobitem->delete();
                    }
                }
            } else {
                $jobcard = new Jobcard;
            }


            if (!$request->id) {
                $last_record = Jobcard::all();

                $last_record_id = Jobcard::latest()->first();

                if ($last_record->isEmpty())
                $jobcard_no = 1;
                else
                $jobcard_no = $last_record_id->id + 1;
            } else {
                $jobcard_no = $request->id;
            }

            $jobcard->user_id = Auth::user()->id;

            //adding bill type (1-CREDIT or 2-CASH)
            $jobcard->bill_type = 0;
            if($request->customer_id)
            $jobcard->customer_id = $request->customer_id;
            else
            {
                $new_customer = new Customer();
                $new_customer->user_id = Auth::user()->id;
                $new_customer->name = strtoupper($request->name);
                $new_customer->mobile = $request->mobile;
                $new_customer->gst_no = $request->gst_no;
                $new_customer->save();
                $jobcard->customer_id = $new_customer->id;

            }

            $jobcard->gst_bill = $request->gst_bill;


            if ($request->vehicle)
            $jobcard->vehicle = $request->vehicle;


            if ($request->register_no) {
                //To make register_no clean ie KL-11-A-134 / KL 11 A 134 / KL-11 A 134 becomes KL11A134
                $register_no = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($request->register_no));
                $jobcard->register_no = $register_no;
            }

            if ($request->chasis_no)
            $jobcard->chasis_no = $request->chasis_no;

            if ($request->odometer)
            $jobcard->odometer = $request->odometer;

            if ($request->expected_date)
            $jobcard->expected_date = $request->expected_date;

            $jobcard->customer_voice = $request->customer_voice;
            $jobcard->technician_voice = $request->technician_voice;
            $jobcard->jobadvisor_id = $request->jobadvisor_id;
            $jobcard->created_date = date('Y-m-d');

            $total_amount = 0;
            $total_discount = 0;
            $total_gst = 0;
            $total_taxable_amount = 0;
            $check = 0;

            $user_id = Auth::user()->id;
            foreach ($request->items as $item) {

                $jobitem = new JobcardItem;
                $jobitem->user_id = $user_id;
                $jobitem->jobcard_id = $jobcard_no;
                if(isset($item['technician_id']))
                $jobitem->technician_id = $item['technician_id'];
                $jobitem->category_id = $item['category_id'];
                if ($jobitem->category_id == 1) {
                    $check = 1;
                }
                $jobitem->product_id = $item['product_id'];
                $jobitem->quantity = $item['quantity'];
                $jobitem->rate = $item['rate'];
                $jobitem->total = $jobitem->quantity * $jobitem->rate;

                //Product table quantity updation (only sale category)
                if ($jobitem->category_id == 1) {
                    $product = Product::FindOrFail($jobitem->product_id);
                    $product->quantity = $product->quantity - $jobitem->quantity;
                    $product->save();
                }

                if ($request->gst_bill == 0) {
                    //finding total_rate by multiplying quantity and rate
                    $jobitem->total_rate =  $jobitem->total;
                } else {
                    $jobitem->discount = $item['discount'];
                    $jobitem->gross_amount = $jobitem->total - $jobitem->discount;
                    $jobitem->gst = $item['gst'];
                    $gst_tax = $jobitem->gross_amount - $jobitem->gross_amount * 100 / (100 + $jobitem->gst);
                    $jobitem->taxable_amount = $jobitem->gross_amount - $gst_tax;
                    $jobitem->cgst = $gst_tax / 2.0;
                    $jobitem->sgst = $gst_tax / 2.0;
                    $jobitem->total_rate =  $jobitem->taxable_amount + $gst_tax;
                    $total_discount =  $total_discount + $jobitem->discount;
                    $total_gst = $total_gst + $gst_tax;
                    $total_taxable_amount = $total_taxable_amount + $jobitem->taxable_amount;
                }


                //finding total amount by adding total_rate of each item
                if ($request->gst_bill == 0) {
                    $total_amount = $total_amount + $jobitem->total_rate;
                } else {
                    $total_amount = $total_amount + $jobitem->total;
                }


                $jobitem->save();
            }

            $jobcard->total_amount =  $total_amount;
            if ($request->gst_bill == 0) {
                $jobcard->total_discount = $request->total_discount;
            } else {
                $jobcard->total_discount = $total_discount;
            }

            $jobcard->total_taxable_amount = $total_taxable_amount;
            $jobcard->total_cgst = $total_gst / 2.0;
            $jobcard->total_sgst = $total_gst / 2.0;

            //finding payable amount
            $jobcard->pay_amount = $jobcard->total_amount - $jobcard->total_discount;

            //finding reward points
            if ($check == 1)
            $reward_points = $jobcard->pay_amount * 0.0025;
            else
            $reward_points = $jobcard->pay_amount * 0.01;

            $jobcard->reward_points = $reward_points;

            $jobcard->save();

            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';
        }
    }


    public function deleteJobcard(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try{

            $jobcard = Jobcard::FindOrFail($request->id);
            $jobitems = JobcardItem::where('jobcard_id', $jobcard->id)->get();

            foreach ($jobitems as $jobitem) {

                //updating quantity under product table
                if ($jobitem->category_id == 1) {
                    $product = Product::FindOrFail($jobitem->product_id);
                    if ($product) {
                        $product->quantity = $product->quantity + $jobitem->quantity;
                        $product->save();
                    } else
                    return "Error";
                }

                //deleting item one by one
                $jobitem->delete();
            }

            //deleting reward points entry in ustomer_rewards table
            $customer_reward = CustomerReward::where('jobcard_no', $jobcard->id)->first();
            if ($customer_reward)
            $customer_reward->delete();

            //delete Payments
            JobcardPayment::where('jobcard_id', $jobcard->id)->delete();


            //deleting sale entry
            $jobcard->delete();
            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';
        }
    }


    public function getJobcards(Request $request)
    {
        $jobcards =  Jobcard::with('items', 'customer', 'payments','advisor','items.product','items.technician')->where('jobcard_status', $request->jobcard_status);

        if ($request->report) {
            if ($request->from_date) {

                $jobcards->where('delivered_date', '>=', $request->from_date);
            }
            if ($request->to_date) {

                $jobcards->where('delivered_date', '<=', $request->to_date);
            }
            if (!$request->from_date && !$request->from_date) {
                $jobcards->whereDate('delivered_date', '=',Carbon::today());
            }
        }




        return $jobcards->orderBy('id', 'desc')->get();
    }

    public function getPaymentBalanceJobcards(Request $request)
    {
        $jobcards =  Jobcard::with('items', 'customer', 'payments','advisor','items.product','items.technician')->where('jobcard_status', 2)->whereColumn('pay_amount' ,'>','received_amount');

        return $jobcards->orderBy('id', 'desc')->get();
    }

    public function mark_jobcardcompleted(Request $request)
    {

        $jobcard = Jobcard::find($request->id);

        if ($jobcard) {
            $jobcard->jobcard_status = 1;
            $jobcard->completed_date = date('Y-m-d H:i:s');
            $jobcard->save();
        } else {
            return "error";
        }

        return "success";
    }

    public function mark_jobcarddelivered(Request $request)
    {
        $jobcard = Jobcard::find($request->id);

        if ($jobcard) {
            $jobcard->jobcard_status = 2;
            $jobcard->delivered_date = date('Y-m-d H:i:s');
            $jobcard->save();
        } else {
            return "error";
        }

        return "success";
    }

    public function backToList(Request $request)
    {
        $jobcard = Jobcard::find($request->id);

        if ($jobcard) {
            $jobcard->jobcard_status = 0;
            $jobcard->completed_date = NULL;
            $jobcard->delivered_date = NULL;
            $jobcard->save();
        } else {
            return "error";
        }

        return "success";
    }
}

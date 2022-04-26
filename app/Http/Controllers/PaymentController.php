<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\CustomerCredit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function save(Request $request)
    {



        $request->validate([
            'customer_id'=>'required',
            'amount' => 'required',
            // 'paytype' => 'required',
        ]);

        DB::beginTransaction();
        try{

            if ($request->id)
            $payment = Payment::FindOrFail($request->id);
            else
            $payment = new Payment;


            if($request->payment_date)
            $payment->payment_date = $request->payment_date;
            else
            $payment->payment_date= Carbon::today();


            $payment->customer_id = $request->customer_id;
            $payment->amount = $request->amount;

            if($request->bad_debt){
            $payment->bad_debt=$request->bad_debt;
            }else{
                $payment->bad_debt=0;
            }

            $payment->pay_type = $request->paytype;
            if($request->remarks)
            // $payment->remarks = $request->remarks;
            $payment->remarks = strtoupper($request->remarks);
            $payment->user_id = Auth::user()->id;
            $payment->save();

            //adding entry to customer_credits
            if ($request->id)
            $customer_credit = CustomerCredit::where('payment_id',$payment->id)->first();
            else
            $customer_credit = new CustomerCredit;

            $customer_credit->user_id = Auth::user()->id;
            $customer_credit->customer_id = $request->customer_id;
            $customer_credit->payment_id = $payment->id;
            $customer_credit->amount = -$request->amount;
            if($payment->bad_debt){
                $customer_credit->remarks = "MARKED AS BAD DEBT";
            }
            
            else{
                $customer_credit->remarks = "PAYMENT AGAINST CREDIT";
            }
            
            $customer_credit->save();

            DB::commit();

            if( $payment->save()){

                $forReciept=Payment::where('customer_id','=',$request->customer_id)->orderBy('id', 'desc')->first();
                return $forReciept;

            }

        } catch(\Exception $e) {
            DB::rollBack();
            return 'Failed';

        }

    }

    public function getPayments(Request $request)
    {

        $payments = Payment::with('customer')->where('credit',1)->orderBy('created_at','DESC');

        if ($request->from_date) {
            $payments->where('payment_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $payments->where('payment_date', '<=', $request->to_date);
        }
        if (!$request->from_date && !$request->to_date) {
            $payments->where('payment_date','=',Carbon::today());

        }

        return $payments->get();


    }

    public function deletePayment(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try{

            $payment = Payment::FindOrFail($request->id);
            $customer_credit = CustomerCredit::where('payment_id',$payment->id)->first();
            $customer_credit->delete();
            $payment->delete();
            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';
        }

    }


    public function markBadDebt(Request $request){

    }
}

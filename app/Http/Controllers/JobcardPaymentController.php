<?php

namespace App\Http\Controllers;
use App\Models\Jobcard;
use App\Models\JobcardPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobcardPaymentController extends Controller
{

    public function get_payments(Request $request) {

        $this->validate($request, [
            'jobcard_id'=> 'required',
        ]);

        $payments = JobcardPayment::where('jobcard_id', $request->jobcard_id)->orderBy('id', 'desc')->get();

        return $payments;
    }

 public function get_all_payments(Request $request) {


        $payments = JobcardPayment::with('jobcard.customer')->orderBy('id', 'desc');
        if ($request->from_date) {
            $payments->where('payment_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $payments->where('payment_date', '<=', $request->to_date);
        }
        return $payments->get();
    }

    public function add_payment(Request $request) {

        $this->validate($request, [
            'jobcard_id'=> 'required',
            'customer_id'=> 'required',
            'pay_type' => 'required',
            'amount' => 'required',
        ]);

        DB::beginTransaction();
        try{

            if($request->id){
                $payment = JobcardPayment::findOrFail($request->id);
                $pre_amount = $payment->amount;
            }
            else{
                $payment = new JobcardPayment;
            }

            $payment->jobcard_id = $request->jobcard_id;
            $jobcard = Jobcard::where('id',$request->jobcard_id)->first();


            $payment->customer_id = $request->customer_id;
            $payment->pay_type = $request->pay_type;
            $payment->amount = $request->amount;

            $payment->	payment_date = today();
            $payment->	user_id = Auth::user()->id;



            if($request->id){
                $paid = $jobcard->received_amount - $pre_amount;
            }
            else
            {
                $paid = $jobcard->received_amount;
            }
            if(($jobcard->pay_amount - $paid) < $request->amount)
            {
                return 'error';
            }
            else{

                $payment->save();
                $amount=JobcardPayment::where('jobcard_id',$request->jobcard_id)->sum('amount');

                $jobcard->received_amount=$amount;
                $jobcard->save();
            }

            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }


    }

    public function delete_payment(Request $request) {

        DB::beginTransaction();
        try{

            $payment = JobcardPayment::findOrFail($request->id);

            $jobcard = Jobcard::where('id',$payment->jobcard_id)->first();
            $payment->delete();
            $amount=JobcardPayment::where('jobcard_id',$jobcard->id)->sum('amount');
            $jobcard->received_amount=$amount;
            $jobcard->save();
            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }
    }

}

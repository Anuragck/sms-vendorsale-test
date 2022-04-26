<?php

namespace App\Http\Controllers;
use App\Models\PurchasePayment;
use App\Models\PurchaseCredit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchasePaymentController extends Controller
{
    public function save(Request $request)
    {

        $request->validate([
            'vendor_id'=>'required',
            'amount' => 'required',
            'pay_type' => 'required',
            'payment_for'=>'required',
        ]);


        if ($request->id)

            $payment = PurchasePayment::FindOrFail($request->id);


        else
            $payment = new PurchasePayment;


        if($request->payment_date)
        {

            $payment->payment_date = $request->payment_date;

        }
        else
            $payment->payment_date= Carbon::today();

        $payment->vendor_id = $request->vendor_id;
        $payment->credit = 1;
        $payment->amount = $request->amount;
        $payment->pay_type = $request->pay_type;

        //1-CreditSale    2-CreditPurchase
        if ($request->payment_for == 1){
            $payment->remarks = "AMOUNT AGAINST CREDIT SALE FOR VENDOR";
        }else{
            $payment->remarks = "PAYMENT AGAINST CREDIT PURCHASE";
        }

        $payment->user_id = Auth::user()->id;
        $payment->save();

        //adding entry to purchase_credits
        if ($request->id)
            $purchase_credit = PurchaseCredit::where('payment_id',$payment->id)->first();
        else
            $purchase_credit = new PurchaseCredit;

        $purchase_credit->user_id = Auth::user()->id;
        $purchase_credit->vendor_id = $request->vendor_id;
        $purchase_credit->payment_id = $payment->id;

        //1-CreditSale    2-CreditPurchase
        if ($request->payment_for == 1){
            $purchase_credit->amount = $request->amount;
            $purchase_credit->remarks = "AMOUNT AGAINST CREDIT SALE FOR VENDOR";
        }else{
            $purchase_credit->amount = -$request->amount;
            $purchase_credit->remarks = "PAYMENT AGAINST CREDIT PURCHASE";
        }



        $purchase_credit->save();

        return 'success';
    }

    public function getPayments(Request $request)
    {

        $payments = PurchasePayment::with('vendor')->where('credit',1)->where('vendor_id',$request->vendor_id)->orderBy('created_at','DESC');

        if ($request->from_date) {
            $payments->where('payment_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $payments->where('payment_date', '<=', $request->to_date);
        }
        return $payments->get();


    }

    public function deletePayment(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $payment = PurchasePayment::FindOrFail($request->id);
        $purchase_credit = PurchaseCredit::where('payment_id',$payment->id)->first();
        $purchase_credit->delete();
        $payment->delete();
        return 'success';

    }
}

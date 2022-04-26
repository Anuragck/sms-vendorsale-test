<?php

namespace App\Http\Controllers;

use App\Models\Closing;
use App\Models\Income;
use App\Models\Expense;
use App\Models\PurchaseLog;
use App\Models\PurchasePayment;
use App\Models\Payment;
use App\Models\Sale;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ClosingController extends Controller
{
    public function addOpening(Request $request)
    {

        if ($request->id)
            $closing = Closing::find($request->id);
        else
            $closing = new Closing;

        $closing->opening_cash = $request->opening_cash;
        $closing->opening_bank = $request->opening_bank;
        $closing->closing_date = Carbon::today();

        $closing->closing_status = 1;

        $closing->save();

        return 'success';
    }

    public function closeAccount(Request $request)
    {
        $request->validate([
            'id' => "required",
        ]);

       
        $today_cash_expense = Expense::whereDate('created_at', Carbon::today())->where('pay_type', 'Cash')->sum('amount');
        $today_bank_expense = Expense::whereDate('created_at', Carbon::today())->where('pay_type', 'Bank')->sum('amount');
        
        $today_cash_income = Income::whereDate('created_at', Carbon::today())->where('pay_type', 'Cash')->sum('amount');
        $today_bank_income = Income::whereDate('created_at', Carbon::today())->where('pay_type', 'Bank')->sum('amount');

       
        $today_cash_purchase_no_credit = PurchaseLog::where('bill_type', 2)->whereDate('purchase_date', Carbon::today())->where('pay_type', 'Cash')->sum('total_amount');
        $today_bank_purchase_no_credit = PurchaseLog::where('bill_type', 2)->whereDate('purchase_date', Carbon::today())->where('pay_type', 'Bank')->sum('total_amount');
        
        $today_cash_purchase_credit_pay = PurchasePayment::where('credit', 1)->whereDate('created_at', Carbon::today())->where('pay_type', 'Cash')->sum('amount');
        $today_bank_purchase_credit_pay = PurchasePayment::where('credit', 1)->whereDate('created_at', Carbon::today())->where('pay_type', 'Bank')->sum('amount');

        $today_cash_purchase = $today_cash_purchase_no_credit + $today_cash_purchase_credit_pay;
        $today_bank_purchase = $today_bank_purchase_no_credit + $today_bank_purchase_credit_pay;

        $today_cash_receipt=Payment::where([['credit',1],['pay_type','Cash']])->whereDate('created_at', Carbon::today())->sum('amount');
        $today_bank_receipt=Payment::where([['credit',1],['pay_type','Bank']])->whereDate('created_at', Carbon::today())->sum('amount');
        

        $today_cash_sale = Sale::where([['bill_type', 2],['pay_type','Cash']])->whereDate('invoice_date', Carbon::today())->sum('pay_amount');
        $today_bank_sale = Sale::where([['bill_type', 2],['pay_type','Bank']])->whereDate('invoice_date', Carbon::today())->sum('pay_amount');

        if ($request->id)
            $closing = Closing::find($request->id);
        else
            return "Failed";

        $closing->today_cash_expense = $today_cash_expense;
        $closing->today_bank_expense = $today_bank_expense;

        $closing->today_cash_income = $today_cash_income;
        $closing->today_bank_income = $today_bank_income;

        $closing->today_cash_purchase = $today_cash_purchase;
        $closing->today_bank_purchase = $today_bank_purchase;

        $closing->today_cash_receipt = $today_cash_receipt;
        $closing->today_bank_receipt = $today_bank_receipt;

        $closing->today_cash_sale = $today_cash_sale;
        $closing->today_bank_sale = $today_bank_sale;

        $closing->closing_cash = ($closing->opening_cash + $today_cash_income + $today_cash_receipt + $today_cash_sale) - ($today_cash_purchase + $today_cash_expense);


        $closing->closing_bank = ($closing->opening_bank + $today_bank_income + $today_bank_receipt + $today_bank_sale) - ($today_bank_purchase + $today_bank_expense);

        $closing->closing_status = 2;
          
        $closing->save();

        $next_day_opening_cash=$closing->closing_cash;
        $next_day_opening_bank=$closing->closing_bank;

    if($closing->save()){
        $closing = new Closing;

        $closing->opening_cash = $next_day_opening_cash;
        $closing->opening_bank = $next_day_opening_bank;
        $closing->closing_date = Carbon::tomorrow();

        $closing->closing_status = 1;

        $closing->save();
    }

        // $closing->closing_cash=$today_cash_income+$today_cash_receipt+$today_cash_sale-$today_cash_purchase-$today_cash_expense;
        // $closing->closing_bank=$today_bank_income+$today_bank_receipt+$today_bank_sale-$today_bank_purchase-$today_bank_expense;

        return 'success';
    }


    public function getClosing()
    {
        $closings = Closing::whereDate('closing_date', Carbon::today())->first();
        return $closings;
    }

    public function getAllClosing(Request $request)
    {

        $closings = Closing::where('closing_status',2)->orderBy('id','DESC');

        if ($request->from_date) {
            $closings->whereDate('closing_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $closings->whereDate('closing_date', '<=', $request->to_date);
        }
        return  $closings->get();


    }
}

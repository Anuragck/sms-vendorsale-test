<?php

namespace App\Http\Controllers;
use App\Models\Income;
use App\Models\Expense;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function save(Request $request)
    {

        $request->validate([
            'income_category_id' => "required",
            'date'=>'required',
            'pay_type' => "required",
            'amount' => "required"
        ]);

        DB::beginTransaction();
        try{

            if($request->id)
            $income = Income::find($request->id);
            else
            $income = new Income;


            $income->income_category_id = $request->income_category_id;
            $income->created_at = $request->date;
            $income->amount = $request->amount;
            $income->pay_type = $request->pay_type;
            if($request->remarks)
            $income->remarks = strtoupper($request->remarks);
            else
            $income->remarks = "NO REMARKS GIVEN";
            $income->user_id = Auth::user()->id;
            $income->save();

            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }
    }
    public function getIncomes(Request $request)
    {

        $incomes = Income::with('incomecategory')->orderBy('created_at','DESC');

        if ($request->from_date) {
            $incomes->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $incomes->whereDate('created_at', '<=', $request->to_date);
        }
        if(!$request->from_date && !$request->to_date)
        $incomes->whereDate('created_at', Carbon::today());
        return  $incomes->get();


    }

    public function getTodayIncomes(){
        $today_incomes = Income::with('incomecategory')->whereDate('created_at', '=', Carbon::today())->orderBy('id', 'desc')->get();
        return $today_incomes;
    }
    public function getIncomesByCategory(Request $request)
    {

        $incomes =  Income::with('incomecategory')->where('income_category_id',$request->income_category_id)->orderBy('created_at','desc')->get();
        return $incomes;
    }

    public function deleteIncome(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $income = Income::find($request->id);
        $income->delete();
        return 'success';
    }


}

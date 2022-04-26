<?php

namespace App\Http\Controllers;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function save(Request $request)
    {
       
        $request->validate([
                       'date'=>'required',
            'pay_type' => "required",
            'amount' => "required"
        ]);

        DB::beginTransaction();
        try{

            if($request->id)
            $expense = Expense::find($request->id);
            else
            $expense = new Expense;
            if($request->expense_category_id)
            {
                $expense->expense_category_id = $request->expense_category_id;
            }
            else
            {
                $expensecategory = new ExpenseCategory;

                $expensecategory->name = strtoupper($request->category_name);
                $expensecategory->user_id = Auth::user()->id;
                $expensecategory->save();
                $expense->expense_category_id= $expensecategory->id;
            }
         
            $expense->created_at = $request->date;
            $expense->amount = $request->amount;
            $expense->pay_type = $request->pay_type;
            if($request->remarks)
            $expense->remarks = strtoupper($request->remarks);
            else
            $expense->remarks = "NO REMARKS GIVEN";
            $expense->user_id = Auth::user()->id;
            $expense->save();

            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }
    }
    public function getExpenses(Request $request)
    {

        $expenses = Expense::with('expensecategory')->orderBy('created_at','DESC');

        if ($request->from_date) {
            $expenses->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $expenses->whereDate('created_at', '<=', $request->to_date);
        }
        if(!$request->from_date && !$request->to_date)
        $expenses->whereDate('created_at', Carbon::today());

        return  $expenses->get();

    }

    public function getTodayExpenses(){
        $today_expenses = Expense::with('expensecategory')->whereDate('created_at', '=', Carbon::today())->orderBy('id', 'desc')->get();
        return $today_expenses;
    }
    public function getExpensesByCategory(Request $request)
    {

        $expenses =  Expense::with('expensecategory')->where('expense_category_id',$request->expense_category_id)->orderBy('created_at','desc')->get();
        return $expenses;
    }
    public function deleteExpense(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $expense = Expense::find($request->id);
        $expense->delete();

        return 'success';


    }


}

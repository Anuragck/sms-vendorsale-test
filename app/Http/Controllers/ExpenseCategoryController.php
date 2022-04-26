<?php

namespace App\Http\Controllers;
use App\Models\ExpenseCategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function getExpenseCategories()
    {
        $expensecategories = ExpenseCategory::orderBy('name')->get();
        return $expensecategories;
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => "required|min:3|max:255|unique:expense_categories,name,$request->id",
        ]);

        if($request->id){
            $expensecategory = ExpenseCategory::find($request->id);
        }
        else
            $expensecategory = new ExpenseCategory;

        $expensecategory->name = strtoupper($request->name);
        $expensecategory->user_id = Auth::user()->id;
        $expensecategory->save();
        return 'success';
    }

    public function deleteExpenseCategory(Request $request) {
        $request->validate([
            'id' => 'required',
        ]);

        $expensecategory = ExpenseCategory::find($request->id);
        $expensecategory->delete();


        return 'success';
    }
}

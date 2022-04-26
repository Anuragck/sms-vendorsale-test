<?php

namespace App\Http\Controllers;
use App\Models\IncomeCategory;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeCategoryController extends Controller
{
    public function getIncomeCategories()
    {
        $incomecategories = IncomeCategory::orderBy('name')->get();
        return $incomecategories;
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => "required|min:3|max:255|unique:income_categories,name,$request->id",
        ]);

        if($request->id){
            $incomecategory = IncomeCategory::find($request->id);
        }
        else
            $incomecategory = new IncomeCategory;

        $incomecategory->name = strtoupper($request->name);
        $incomecategory->user_id = Auth::user()->id;
        $incomecategory->save();
        return 'success';
    }

    public function deleteIncomeCategory(Request $request) {
        $request->validate([
            'id' => 'required',
        ]);

        $incomecategory = IncomeCategory::find($request->id);
        $incomecategory->delete();


        return 'success';
    }
}

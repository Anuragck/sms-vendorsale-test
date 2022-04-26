<?php

namespace App\Http\Controllers;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function getCompany(Request $request)

    {
       $company =  Company::where('id',$request->company_id)->first();
       return $company;
    }
}

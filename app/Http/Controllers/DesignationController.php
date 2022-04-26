<?php

namespace App\Http\Controllers;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function getDesignations()
    {

        $designations = Designation::orderBy('name','ASC')->get();
        return  $designations;


    }
}

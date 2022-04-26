<?php

namespace App\Http\Controllers;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class VendorController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'name' => "required|min:3|max:255|unique:vendors,name,$request->id",
            'mobile' => "required|min:10|max:10|unique:vendors,mobile,$request->id"
        ]);


        if ($request->id) {


            $vendor = Vendor::find($request->id);
        } else
            $vendor = new Vendor;

        $vendor->name = strtoupper($request->name);
        $vendor->mobile = $request->mobile;
        if($request->location)
            $vendor->location = strtoupper($request->location);
        if($request->address_1)
            $vendor->address_1 = strtoupper($request->address_1);
        if($request->address_2)
            $vendor->address_2 = strtoupper($request->address_2);
        if($request->state)
            $vendor->state = strtoupper($request->state);
        if($request->pincode)
            $vendor->pincode = $request->pincode;
        if($request->gst_no)
            $vendor->gst_no = strtoupper($request->gst_no);
        $vendor->user_id = Auth::user()->id;
        $vendor->save();
        return 'success';
    }

    public function getVendors(Request $request)
    {

        
        $vendors =  Vendor::orderBy('name', 'ASC')->get();

        return $vendors;
    }

    public function deleteVendor(Request $request)
    {
        if ($request->id) {
            $vendor = Vendor::find($request->id);
            $vendor->delete();
            return 'success';
        } else {

            return "failed";
        }
    }
}

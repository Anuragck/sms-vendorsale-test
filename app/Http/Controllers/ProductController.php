<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'product_category'=>'required',
            'name' => "required|unique:products,name,$request->id"


            // .$this->product->id
        ]);

        DB::beginTransaction();
        try{

            if ($request->id)
            $product = Product::FindOrFail($request->id);
            else
            $product = new Product;

            $product->name = strtoupper($request->name);
            $product->category_id = $request->product_category;
            $product->user_id = Auth::user()->id;

            if($request->opening_quantity)
            {
                $product->quantity = $request->opening_quantity;
                $product->opening_quantity = $request->opening_quantity;
            }
            else
            { 
                if($request->product_category==1 && $request->product_category==2)
                {
                    $product->quantity = 0;
                    $product->opening_quantity = 0;
                }
               

            }
            if($request->minimum_qty)
            $product->minimum_qty =  $request->minimum_qty;
            if($request->part_no)
            $product->part_no =  $request->part_no;
            if($request->hsn_code)
            $product->hsn_code =  $request->hsn_code;
            if($request->brand)
            $product->brand =  $request->brand;
            if($request->location)
            $product->location =  $request->location;
            if($request->reward_points)
            {
                $product->reward_points = $request->reward_points;
            }
            $product->gst = $request->gst;
            $product->mrp = $request->mrp;
            if($request->discount)
            $product->discount =  $request->discount;

            $product->save();

            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }

    }

    public function getProducts()
    {

        $products = Product::with('category')->orderBy('name','ASC')->get();
        return  $products;

    }


    public function getStockProducts()
    {

        $products = Product::with('category')->orderBy('name','ASC')->where('category_id' ,'!=',3)->get();
        return  $products;

    }


    public function getServiceProducts()
    {

        $products = Product::with('category')->orderBy('name','ASC')->where('category_id' ,'=',3)->get();
        return  $products;

    }

    public function getSaleProducts()
    {

        $products = Product::with('category')->orderBy('name','ASC')->where('category_id' ,'=',1)->get();
        return  $products;

    }

    public function deleteProduct(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $product = Product::find($request->id);
        $product->delete();
        return 'success';

    }

}

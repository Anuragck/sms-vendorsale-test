<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Adjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            // 'customer_id'=>'required',
            'product_id'=>'required',
            'adjustment_action'=>'required',
            'adjustment_quantity'=>'required'


        ]);
        DB::beginTransaction();
        try{


            $adjustment = new Adjustment;

            //adjustment_action:1(increase quantity)
            //adjustment_action:2(decrease quantity)

            $adjustment->product_id = $request->product_id;
            if( $request->adjustment_action == "1")
            $adjustment->increase_quantity = $request->adjustment_quantity;
            else
            $adjustment->increase_quantity =0;
            if( $request->adjustment_action == "2")
            $adjustment->decrease_quantity = $request->adjustment_quantity;
            else
            $adjustment->decrease_quantity =0;
            $adjustment->user_id = Auth::user()->id;
            $adjustment->save();

            //updating product quantity

            $product = Product::where('id',$request->product_id)->first();
            if($product->category_id<3)
            {
                if( $request->adjustment_action == '1')
                $product->quantity = $product->quantity + $request->adjustment_quantity;
                if( $request->adjustment_action == '2')
                $product->quantity = $product->quantity - $request->adjustment_quantity;
            }
            $product->save();

            DB::commit();

            return 'success';
        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }
    }

    public function getAdjustments()
    {

        $adjustments = Adjustment::with('product')->orderBy('created_at','DESC')->get();
        return  $adjustments;

    }

    public function deleteAdjustment(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try{

            $adjustment = Adjustment::FindOrFail($request->id);
            $product = Product::where('id',$adjustment->product_id)->first();

            if($product->category_id<3)
            {
                if( $adjustment->increase_quantity)
                $product->quantity = $product->quantity - $adjustment->increase_quantity;
                if( $adjustment->decrease_quantity)
                $product->quantity = $product->quantity + $adjustment->decrease_quantity;
            }

            $product->save();
            $adjustment->delete();

            DB::commit();

            return 'success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'failed';

        }

    }
}

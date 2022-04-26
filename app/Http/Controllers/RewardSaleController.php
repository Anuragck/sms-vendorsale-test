<?php

namespace App\Http\Controllers;
use App\Models\RewardSale;
use App\Models\CustomerReward;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardSaleController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'customer_id'=>'required',
        ]);

        DB::beginTransaction();
        try{

            if ($request->id) {


                $reward_sale = RewardSale::FindOrFail($request->id);
                $reward_no = $reward_sale->reward_no;

                //updating product
                $product = Product::FindOrFail($reward_sale->item_id);
                if($product)
                {
                    $product->quantity = $product->quantity + $reward_sale->quantity;
                    $product->save();
                }
                else
                {
                    return "Error";
                }
                //deleting reward points entry in ustomer_rewards table
                $customer_reward = CustomerReward::where('reward_no', $reward_sale->reward_no)->first();
                if ($customer_reward)
                $customer_reward->delete();

            }
            else
            $reward_sale = new RewardSale;

            if (!$request->id) {
                $last_record = RewardSale::all();

                $last_record_id = RewardSale::latest()->first();

                if ($last_record->isEmpty())
                $reward_no = 1;
                else
                $reward_no = $last_record_id->id + 1;
            }
            $reward_sale->reward_no= $reward_no;
            $reward_sale->customer_id = $request->customer_id;
            $reward_sale->item_id = $request->item_id;
            $reward_sale->quantity = $request->quantity;
            $reward_sale->user_id = Auth::user()->id;


            //changing product qty
            $product = Product::FindOrFail($request->item_id);

            //finding redeemed points
            $redeemed_points=$request->quantity*$product->reward_points;
            $reward_sale->redeemed_points =$redeemed_points;

            $points = CustomerReward::where('customer_id', $request->customer_id)->sum('points');
            if($redeemed_points<$points && $points > 500 )
            {
                $product->quantity = $product->quantity - $request->quantity;
                $product->save();
            }
            else
            return 'Error';


            //updating reward points in customer_rewards table
            $customer_reward = new CustomerReward;
            $customer_reward->user_id = Auth::user()->id;
            $customer_reward->customer_id = $request->customer_id;
            $customer_reward->type = "DEBIT";
            $customer_reward->points = -$redeemed_points;
            $customer_reward->reward_no = $reward_no;
            $customer_reward->remarks = "REDEEM REWARDS";
            $customer_reward->save();

            $reward_sale->save();

            DB::commit();

            return 'Success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'Failed';
        }
    }

    public function getRewardSales()
    {
        $reward_sales = RewardSale::with('product','customer')->orderBy('created_at','DESC')->get();
        return  $reward_sales;

    }

    public function deleteRewardSale(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try{

            $reward_sale = RewardSale::FindOrFail($request->id);

            //updating product
            $product = Product::FindOrFail($reward_sale->item_id);
            if($product)
            {
                $product->quantity = $product->quantity + $reward_sale->quantity;
                $product->save();
            }
            else
            {
                return "Error";
            }
            //deleting reward points entry in customer_rewards table
            $customer_reward = CustomerReward::where('reward_no', $reward_sale->reward_no)->first();
            if ($customer_reward)
            $customer_reward->delete();

            $reward_sale->delete();

            DB::commit();

            return 'Success';

        } catch(\Exception $e) {
            DB::rollBack();
            return 'Failed';
        }
    }
}

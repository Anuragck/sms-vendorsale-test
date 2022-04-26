<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseLog;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function save(Request $request)
    {

        foreach ($request->items as $item) {
            if ($item['order_qty'] == null) {
                return 'items_error';
            }
        }

        if ($request->items == []) {
            return "items_array_error";
        }



        // DB::beginTransaction();
        // try{


        if ($request->id) {
            $purchase_order = PurchaseOrder::FindOrFail($request->id);

            $purchase_order_items = PurchaseOrderItem::where('order_id', $purchase_order->id)->delete();

        } else {
            $purchase_order = new PurchaseOrder;

            $statement = DB::select("SHOW TABLE STATUS LIKE 'purchase_orders'");
            $purchase_order_id = $statement[0]->Auto_increment;
        }



        $purchase_order->user_id = Auth::user()->id;
        if ($request->vendor_name) {
            $vendor = new Vendor;

            $vendor->user_id = Auth::user()->id;
            $vendor->name = strtoupper($request->vendor_name);
            $vendor->mobile = $request->vendor_mobile;
            $vendor->save();
            $purchase_order->vendor_id = $vendor->id;
        }
        if ($request->vendor_id)
            $purchase_order->vendor_id = $request->vendor_id;


        $purchase_order->remarks = $request->remarks;

        /*********************************/ //////
        //finding total products and purchase order quantities
        $total_products = 0;
        $total_qty = 0;
        $total_amount = 0;
        $total_tax = 0;
        $total_taxable_amount = 0;

        foreach ($request->items as $item) {


            $purchase_order_item = new PurchaseOrderItem;

            $purchase_order_item->order_id = $purchase_order_id;
            if (isset($item['product_id'])) {
                $purchase_order_item->product_id = $item['product_id'];
            }
            else
            {
                $product = new Product;
                $product->name = strtoupper($item['product_name']);
                $product->category_id = 1;
                $product->user_id = Auth::user()->id;
                $product->quantity = 0;
                $product->opening_quantity = 0;
                $product->save();
                $purchase_order_item->product_id = $product->id;

            }


            $purchase_order_item->order_qty = $item['order_qty'];




            //addition to find total products and purchase quantities
            $total_products = $total_products + 1;
            $total_qty = $total_qty + $purchase_order_item->order_qty;

            $purchase_order_item->save();
        }

        $purchase_order->total_products = $total_products;
        $purchase_order->total_qty = $total_qty;
        $purchase_order->save();

        // DB::commit();
        return 'success';


        // } catch(\Exception $e)
        // {
        //     DB::rollBack();
        //     return 'failed';
        // }
    }





    public function getPurchaseOrders(Request $request)
    {

        $purchase_orders =  PurchaseOrder::with('orderItems');

        if ($request->from_date) {

            $purchase_orders->where('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {

            $purchase_orders->where('created_at', '<=', $request->to_date);
        }
        if (!$request->from_date && !$request->to_date) {
            $purchase_orders->where('created_at', '=', Carbon::today());
        }

        return $purchase_orders->orderBy('created_at', 'desc')->get();
    }


    public function deletePurchaseOrder(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $purchase_order = PurchaseOrder::FindOrFail($request->id);
            $purchase_items = PurchaseOrderItem::where('order_id', $purchase_order->id)->delete();


            //deleting purchase entry
            $purchase_order->delete();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return 'failed';
        }
    }

    public function getItemsByVendor(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $vendor = Vendor::FindOrFail($request->id);
            $purchase_items = PurchaseLog::with('purchaseItems')->where('vendor_id', $vendor->id)->get();

            $products = [];
            foreach ($purchase_items as $purchase_item) {
                $items = $purchase_item->purchaseItems;
                foreach ($items as $item)
                    array_push($products, $item['product_id']);

            }
            $unique_products = array_unique($products);
            $productdetails = [];
            foreach($unique_products as $product)
            {
                $product_info= Product::where('id',$product)->select('id','part_no','name','quantity')->first();

                array_push($productdetails,$product_info);


            }
            return $productdetails;
        } catch (\Exception $e) {
            DB::rollBack();
            return 'failed';
        }
    }
}

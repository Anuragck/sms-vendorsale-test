<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceSendToMail;
use App\Models\CustomerCredit;
use App\Models\PurchaseCredit;
use App\Models\CustomerReward;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Adjustment;
use App\Models\Company;
use App\Models\PurchaseItem;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function consolidateRewardReport()
    {
        $credits = CustomerReward::with('customer')->where('created_at', '!=', NULL)->get();
        $groups = $credits->groupBy('customer_id');



        $groupwithsum = $groups->mapWithKeys(function ($group, $key) {
            return
                [
                    $key =>
                    [
                        'customer_id' => $key, // $key is what we grouped by, it'll be constant by each  group of rows
                        'customer_name' => Customer::where('id', $key)->value('name'),
                        'customer_mobile' => Customer::where('id', $key)->value('mobile'),
                        'transactions_count' => $group->count(),
                        'points' => $group->sum('points'),

                    ]
                ];
        });
        return $groupwithsum->sortByDesc('points')->values();
    }


    public function rewardReportByCustomer(Request $request)
    {

        $rewards = CustomerReward::with('customer:id,name,mobile')->where('customer_id', $request->customer_id)->get();
        return $rewards;
    }

    public function consolidateCreditReport()
    {
        $credits = CustomerCredit::with('customer')->where('created_at', '!=', NULL)->get();
        $groups = $credits->groupBy('customer_id');



        $groupwithsum = $groups->mapWithKeys(function ($group, $key) {
            return
                [
                    $key =>
                    [
                        'customer_id' => $key, // $key is what we grouped by, it'll be constant by each  group of rows
                        'customer_name' => Customer::where('id', $key)->value('name'),
                        'customer_mobile' => Customer::where('id', $key)->value('mobile'),
                        'transactions_count' => $group->count(),
                        'amount' => $group->sum('amount'),

                    ]
                ];
        });
        return $groupwithsum->sortByDesc('amount')->values();
    }

    public function consolidatePurchaseCreditReport()
    {
        $credits = PurchaseCredit::where('created_at', '!=', NULL)->get();
        $groups = $credits->groupBy('vendor_id');


        $groupwithsum = $groups->mapWithKeys(function ($group, $key) {
            return
                [
                    $key =>
                    [
                        'vendor_id' => $key, // $key is what we grouped by, it'll be constant by each  group of rows
                        'vendor_name' => Vendor::where('id', $key)->value('name'),
                        'transactions_count' => $group->count(),
                        'amount' => $group->sum('amount'),

                    ]
                ];
        });
        return $groupwithsum->sortByDesc('amount')->values();
    }


    public function creditReportByCustomer(Request $request)

    {

        $credits = CustomerCredit::with('customer','payment')->where('customer_id', $request->customer_id)->get();
        return $credits;
    }

    public function getCreditSalesByInvoice(Request $request)
    {
        $credit_sales =  Sale::with('items', 'customer')->where([['customer_id', $request->customer_id], ['bill_type', 1]])->orderBy('id', 'desc')->get();
        return $credit_sales;
    }

    public function itemReport(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $product =  Product::where('id', $request->product_id)->first();
        $adjustments = Adjustment::select('increase_quantity', 'decrease_quantity', 'created_at')->where('product_id', $request->product_id)->get();
        $transactions = [];
        $array1 = [];
        foreach ($adjustments as $key => $value) {

            if ($value->increase_quantity > 0) {
                $transactions[$key]['type'] = 1;
                $transactions[$key]['description'] = "Increased through adjustments";
                $transactions[$key]['quantity'] = $value->increase_quantity;
                $transactions[$key]['date'] = date($value->created_at);
            } else if ($value->decrease_quantity > 0) {
                $transactions[$key]['type'] = 2;
                $transactions[$key]['description'] = "Decreased through adjustments";
                $transactions[$key]['quantity'] = $value->decrease_quantity;
                $transactions[$key]['date'] = date($value->created_at);
            }
            array_push($array1, $transactions[$key]);
        }

        //return $transactions;
        $sale_items = SaleItem::select('sale_id', 'quantity', 'created_at')->where('product_id', $request->product_id)->get();

        foreach ($sale_items as $key => $value) {



            $transactions[$key]['type'] = 2;
            $transactions[$key]['description'] = "Decreased through invoice " . +$value->sale_id;
            $transactions[$key]['quantity'] = $value->quantity;
            $transactions[$key]['date'] = date($value->created_at);

            array_push($array1, $transactions[$key]);
        }

        $purchase_items = PurchaseItem::select('purchase_id', 'purchase_qty', 'created_at')->where('product_id', $request->product_id)->get();

        foreach ($purchase_items as $key => $value) {



            $transactions[$key]['type'] = 1;
            $transactions[$key]['description'] = "Increased through purchase " . +$value->purchase_id;
            $transactions[$key]['quantity'] = $value->purchase_qty;
            $transactions[$key]['date'] = date($value->created_at);

            array_push($array1, $transactions[$key]);
        }
        $sale_return_items = SaleReturnItem::select('sale_return_id', 'return_qty', 'created_at')->where('product_id', $request->product_id)->get();

        foreach ($sale_return_items as $key => $value) {



            $transactions[$key]['type'] = 1;
            $transactions[$key]['description'] = "Increased through sale return " . +$value->sale_return_id;
            $transactions[$key]['quantity'] = $value->return_qty;
            $transactions[$key]['date'] = date($value->created_at);

            array_push($array1, $transactions[$key]);
        }

        $sorted = collect($array1)->sortBy('date')->values()->all();
        $item_details[] = [
            'product_name'  => $product->name,
            'opening_quantity'  => $product->opening_quantity,
            'current_quantity'  => $product->quantity,
            'transactions'       => $sorted,

        ];
        return $item_details;
    }

    public function profitReport(Request $request)
    {
        $expenses = Expense::with('expensecategory')->where('created_at', '!=', NULL)->get();
        $expense_groups = $expenses->groupBy('expense_category_id');



        $expense_groups_filter = $expense_groups->mapWithKeys(function ($group, $key) {
            return
                [
                    $key =>
                    [
                        'expense_category_id' => $key, // $key is what we grouped by, it'll be constant by each  group of rows
                        'expense_category' => ExpenseCategory::where('id', $key)->value('name'),
                        'expense_amount' => $group->sum('amount'),

                    ]
                ];
        });
        $expenses_groupby_category = $expense_groups_filter->sortByDesc('expense_amount')->values();

        $details_array[] = [
            'expenses_groupby_category' => $expenses_groupby_category,
            'total_expenses' => $expenses->sum('amount'),
            'total_cash_expenses' => $expenses->where('pay_type', 'Cash')->sum('amount'),
            'total_bank_expenses' => $expenses->where('pay_type', 'Bank')->sum('amount'),

        ];
        return $details_array;
    }

    public function pdfInvoice(Request $request)
    {

        $invoice =  Sale::with('items', 'customer', 'items.product')->where('id', '=', $request->id)->first();
        $company = Company::all();
        $pdf = PDF::loadView('pdf.invoice', ['invoice' => $invoice, 'companyDetails' => $company, 'gst' => $request->gst_bill]);
        return $pdf->stream('invoice.pdf');
        //  return $pdf->output();

    }

    public function pdfQuotation(Request $request)
    {

        $quotation =  Quotation::with('items', 'customer', 'items.product')->where('id', '=', $request->id)->first();
        $company = Company::all();
        $pdf = PDF::loadView('pdf.quotation', ['quotation' => $quotation, 'companyDetails' => $company, 'gst' => $request->gst_bill]);
        return $pdf->stream('quotation.pdf');
        //  return $pdf->output();

    }


    public function invoiceSendData(Request $request)
    {

        DB::beginTransaction();
        try{
        $url = 'http://sms.test/get-invoice?id=' . $request->invoice_no . '&gst_bill=' . $request->gst_bill;
        $customer_name = $request->customer_name;

        $customer_email = $request->customer_email;


        $invoice_details = [
            'customer_name' => $customer_name,
            'link' => $url,
            'message' => 'Thank you for your purchase',
        ];

       Mail::to($customer_email)->send(new InvoiceSendToMail($invoice_details));

       $invoice =  Sale::where('invoice_no', '=', $request->invoice_no)->first();

        if($invoice->email_status == 0){
         $invoice->email_status=1;
         $invoice->save();
         DB::commit();
        }


         return 'success';
    }catch(\Exception $e) {
        DB::rollBack();
        return 'failed';

    }

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Jobcard;
use App\Models\JobcardItem;
use App\Models\JobcardPayment;
use App\Models\CustomerReward;
use App\Models\CustomerCredit;
use App\Models\PurchaseLog;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;


class DashboardController extends Controller
{
    public function report(Request $request)
    {

        $products = Product::where('category_id', 1)
        ->select('name', 'quantity')
        ->orderBy('quantity')->get();

        $filtered = $products->filter(function ($product) {
            if ($product->quantity < 20) {
                return $product;
            }
        });

        $low_stock_item_names = [];
        $low_stock_item_quantities = [];
        foreach ($filtered as $product) {
            array_push($low_stock_item_names, $product->name);
            array_push($low_stock_item_quantities, $product->quantity);
        }

        $credits = CustomerCredit::with('customer')->get();
        $groups = $credits->groupBy('customer_id');

        $groupwithsum = $groups->mapWithKeys(function ($group, $key) {
            return
            [
                $key =>
                [
                    'customer_id' => $key, // $key is what we grouped by, it'll be constant by each  group of rows
                    'customer_name' => Customer::where('id', $key)->value('name'),
                    'amount' => $group->sum('amount'),

                    ]
                ];
            });

            $payments = Payment::with('customer:id,name,mobile')->where('bad_debt','!=',1);
            $incomes = Income::select('id','income_category_id', 'amount', 'pay_type', 'created_at')->with('incomecategory:id,name');
            $expenses = Expense::select('id','expense_category_id', 'amount', 'pay_type', 'created_at')->with('expensecategory:id,name');
            $sales =  Sale::with('items', 'customer');
            $sale_items =  SaleItem::with('product:id,name');
            $jobcards =  Jobcard::with('items', 'customer');
            $jobcard_items =  JobcardItem::with('product:id,name');
            $jobcard_payments = JobcardPayment::with('customer:id,name,mobile');
            $cashpurchases = PurchaseLog::with('purchaseItems')->where('bill_type',2);


            //for dashboard data export date
            if ($request->from_date != '' && $request->to_date != '') {
                $print_date = Carbon::parse($request->from_date)->format('d-m-Y') . ' to ' . Carbon::parse($request->to_date)->format('d-m-Y');
            } else if ($request->from_date != '' && $request->to_date == '') {
                $print_date = 'Report From  ' . Carbon::parse($request->from_date)->format('d-m-Y');
            } else if ($request->from_date == '' && $request->to_date != '') {
                $print_date = 'Report Upto  ' . Carbon::parse($request->to_date)->format('d-m-Y');
            } else {
                $print_date = Carbon::now()->format('d-m-Y');
            }


            if ($request->from_date) {

                $sales =   $sales->whereDate('invoice_date', '>=', $request->from_date);
                $payments = $payments->whereDate('payment_date', '>=', $request->from_date);
                $incomes = $incomes->whereDate('created_at', '>=', $request->from_date);
                $expenses = $expenses->whereDate('created_at', '>=', $request->from_date);
                $sale_items = $sale_items->whereDate('created_at', '>=', $request->from_date);
                $cashpurchases = $cashpurchases->whereDate('purchase_date', '>=', $request->from_date);
                $jobcards =   $jobcards->whereDate('created_date', '>=', $request->from_date);
                $jobcard_items =  $jobcard_items->whereDate('created_at', '>=', $request->from_date);
                $jobcard_payments =   $jobcard_payments->whereDate('payment_date', '>=', $request->from_date);

            }
            if ($request->to_date) {

                $sales=$sales->whereDate('invoice_date', '<=', $request->to_date);
                $payments = $payments->whereDate('payment_date', '<=', $request->to_date);
                $incomes = $incomes->whereDate('created_at', '<=', $request->to_date);
                $expenses = $expenses->whereDate('created_at', '<=', $request->to_date);
                $sale_items = $sale_items->whereDate('created_at', '<=', $request->to_date);
                $cashpurchases = $cashpurchases->whereDate('purchase_date', '<=', $request->to_date);
                $jobcards =   $jobcards->whereDate('created_date','<=', $request->to_date);
                $jobcard_items =  $jobcard_items->whereDate('created_at', '<=', $request->to_date);
                $jobcard_payments =   $jobcard_payments->whereDate('payment_date','<=', $request->to_date);

            }
            if (!$request->from_date && !$request->to_date) {
                $sales =  $sales->whereDate('invoice_date', Carbon::today());
                $payments =  $payments->whereDate('payment_date', Carbon::today());
                $incomes = $incomes->whereDate('created_at', Carbon::today());
                $expenses = $expenses->whereDate('created_at', Carbon::today());
                $sale_items = $sale_items->whereDate('created_at', Carbon::today());
                $cashpurchases = $cashpurchases->whereDate('purchase_date', Carbon::today());
                $jobcards =   $jobcards->whereDate('created_date', Carbon::today());
                $jobcard_items =  $jobcard_items->whereDate('created_at',  Carbon::today());
                $jobcard_payments =   $jobcard_payments->whereDate('payment_date', Carbon::today());
            }

            $sales =  $sales->get();
            $payments =  $payments->orderBy('invoice_no')->get();
            $incomes = $incomes->get();
            $expenses = $expenses->get();
            $cashpurchases = $cashpurchases->get();
            $jobcards = $jobcards->get();
            $jobcard_items = $jobcard_items->get();
            $jobcard_payments = $jobcard_payments->get();

            $sale_items = $sale_items->get();

            $groups = $sale_items->groupBy('product_id');

            $item_group = $groups->mapWithKeys(function ($group, $key)
            {
                return
                [
                    $key =>
                    [
                        'id' => $key, // $key is what we grouped by, it'll be constant by each  group of rows
                        'product_name' => Product::where('id',$key)->withTrashed()->value('name'),
                        'quantity' => $group->count(),
                        'amount' => $group->sum('total_rate'),

                        ]
                    ];
                });



                $transactions =[];
                $array1=[];
                foreach ($payments as $key => $value) {

                    if($value->invoice_no)
                    {
                        $items = SaleItem::with('product')->where('sale_id',$value->invoice_no)->get();
                        $itemdetails='';
                        foreach ($items as $item)
                        {
                            $itemdetails .= $item->product->name."(".$item->quantity.")".",";

                        }
                        $transactions[$key]['date'] = Carbon::parse($value->payment_date)->format('M d Y');
                        $transactions[$key]['description'] ="Sale Payment : ".Customer::where('id', $value->customer_id)->value('name')." (Invoice no :".strval($value->invoice_no).") Products-".$itemdetails;
                        $transactions[$key]['credit'] = $value->amount;
                        $transactions[$key]['debit'] = 0;
                        $transactions[$key]['paytype'] = $value->pay_type;


                    }
                    else
                    {
                        $transactions[$key]['date'] = Carbon::parse($value->payment_date)->format('M d Y');
                        $transactions[$key]['description'] = "Credit Payment : ".Customer::where('id', $value->customer_id)->value('name');
                        $transactions[$key]['credit'] = $value->amount;
                        $transactions[$key]['debit'] = 0;
                        $transactions[$key]['paytype'] = $value->pay_type;

                    }
                    array_push($array1,$transactions[$key]);

                }

                foreach ($expenses as $key => $value) {

                    $transactions[$key]['date'] = Carbon::parse($value->created_at)->format('M d Y');
                    $transactions[$key]['description'] ="Expense : ".ExpenseCategory::where('id', $value->expense_category_id)->value('name');
                    $transactions[$key]['debit'] = $value->amount;
                    $transactions[$key]['credit'] = 0;
                    $transactions[$key]['paytype'] = $value->pay_type;




                    array_push($array1,$transactions[$key]);

                }


                foreach ($cashpurchases as $key => $value) {

                    $transactions[$key]['date'] = Carbon::parse($value->purchase_date)->format('M d Y');
                    $transactions[$key]['description'] ="Purchase";
                    $transactions[$key]['debit'] = $value->total_amount;
                    $transactions[$key]['credit'] = 0;
                    $transactions[$key]['paytype'] = $value->pay_type;




                    array_push($array1,$transactions[$key]);

                }

                foreach ($incomes as $key => $value) {

                    $transactions[$key]['date'] = Carbon::parse($value->created_at)->format('M d Y');
                    $transactions[$key]['description'] = "Income : ".IncomeCategory::where('id', $value->income_category_id)->value('name');
                    $transactions[$key]['debit'] = 0;
                    $transactions[$key]['credit'] = $value->amount;
                    $transactions[$key]['paytype'] = $value->pay_type;




                    array_push($array1,$transactions[$key]);

                }

                foreach ($jobcard_payments as $key => $value) {


                    $transactions[$key]['date'] = Carbon::parse($value->payment_date)->format('M d Y');
                    $transactions[$key]['description'] ="Jobcard Payment : ".Customer::where('id', $value->customer_id)->value('name')." (Jobcard no :".strval($value->jobcard_id).")";
                    $transactions[$key]['credit'] = $value->amount;
                    $transactions[$key]['debit'] = 0;
                    $transactions[$key]['paytype'] = $value->pay_type;
                    array_push($array1,$transactions[$key]);

                }

                $sorted= collect($array1)->values()->all();
                //expense category wise

                $expense_groups = $expenses->groupBy('expense_category_id');

                $expense_groups_filter = $expense_groups->mapWithKeys(function ($group1, $key1) {
                    return
                    [
                        $key1 =>
                        [
                            'expense_category_id' => $key1, // $key is what we grouped by, it'll be constant by each  group of rows
                            'expense_category' => ExpenseCategory::where('id', $key1)->value('name'),
                            'expense_amount' => $group1->sum('amount'),

                            ]
                        ];
                    });


                    $expenses_groupby_category = $expense_groups_filter->sortByDesc('expense_amount')->values();

                    $piechart_expense_category_name = [];
                    $piechart_expense_category_amount = [];
                    foreach ($expenses_groupby_category as $expense_item) {

                        array_push($piechart_expense_category_name, $expense_item['expense_category']);
                        array_push($piechart_expense_category_amount, $expense_item['expense_amount']);
                    }
                    array_push($piechart_expense_category_name,'Purchases');
                    array_push($piechart_expense_category_amount,$cashpurchases->sum('total_amount'));
                    $piechart_income_category_names = ['jobcard payments','sale payments', 'credit payments', 'other incomes'];
                    $piechart_income_category_values = [];
                    array_push($piechart_income_category_values,  $jobcard_payments->sum('amount'));
                    array_push($piechart_income_category_values,  $payments->where('credit', 0)->sum('amount'));
                    array_push($piechart_income_category_values, $payments->where('credit', 1)->sum('amount'));
                    array_push($piechart_income_category_values, $incomes->sum('amount'));


                    $piechart_income_payment_names = ['Cash', 'Bank'];
                    $cash = $payments->where('pay_type', 'Cash')->sum('amount') + $incomes->where('pay_type', 'Cash')->sum('amount')+$jobcard_payments->where('pay_type', 'Cash')->sum('amount') ;
                    $bank = $payments->where('pay_type', 'Bank')->sum('amount') + $incomes->where('pay_type', 'Bank')->sum('amount')+$jobcard_payments->where('pay_type', 'Bank')->sum('amount') ;;
                    $piechart_income_payment_values = [$cash, $bank];

                    $total_expenses = $expenses->sum('amount')+$cashpurchases->sum('total_amount');
                    $total_cash_expenses = $expenses->where('pay_type', 'Cash')->sum('amount')+$cashpurchases->where('pay_type', 'Cash')->sum('total_amount');
                    $total_bank_expenses = $expenses->where('pay_type', 'Bank')->sum('amount')+$cashpurchases->where('pay_type', 'Bank')->sum('total_amount');;

                    $total_incomes = $cash + $bank;
                    $total_profit = $total_incomes - $total_expenses;


                    $total_bank_profit = $bank - $total_bank_expenses;
                    $total_cash_profit = $cash - $total_cash_expenses;

                    $details[] = [

                        'total_sales'  => $sales->count(),
                        'total_sale_amount'  => $sales->sum('pay_amount'),
                        'total_sale_credit_amount'  => $sales->where('bill_type', 1)->sum('pay_amount'),
                        'total_itemsale_amount'  => $sale_items->where('category_id', 1)->sum('total_rate'),
                        'total_servicesale_amount'  => $sale_items->where('category_id', 3)->sum('total_rate'),
                        'sale_item_details' => $sale_items->where('category_id', 1),
                        'service_item_details' => $sale_items->where('category_id', 3),
                        'low_stock_items' => $filtered,
                        'low_stock_item_names' => $low_stock_item_names,
                        'low_stock_item_quantities' => $low_stock_item_quantities,
                        'piechart_income_category_names' => $piechart_income_category_names,
                        'piechart_income_category_values' => $piechart_income_category_values,
                        'piechart_income_payment_names' => $piechart_income_payment_names,
                        'piechart_income_payment_values' => $piechart_income_payment_values,
                        'piechart_expense_category_name' => $piechart_expense_category_name,
                        'piechart_expense_category_amount' => $piechart_expense_category_amount,
                        'customer_with_credits' => $groupwithsum->sortByDesc('amount')->values()->take(20),
                        'total_payments_received' => $cash + $bank,
                        'total_cash_payments_received' => $cash,
                        'total_bank_payments_received' => $bank,
                        'Total Payments' => $payments,
                        'total_incomes_received' => $incomes->sum('amount'),
                        'total_cash_incomes_received' => $incomes->where('pay_type', 'Cash')->sum('amount'),
                        'total_bank_incomes_received' => $incomes->where('pay_type', 'Bank')->sum('amount'),
                        'total_expenses_received' => $total_expenses,
                        'total_cash_expenses_received' => $total_cash_expenses,
                        'total_bank_expenses_received' => $total_bank_expenses,
                        'expenses_category_wise' => $expenses_groupby_category,
                        'total_profit' => $total_profit,
                        'total_bank_profit' => $total_bank_profit,
                        'total_cash_profit' => $total_cash_profit,
                        'expense_list' => $expenses,
                        'transactions' => $sorted,
                        'item_group'=>  $item_group->sortByDesc('amount')->values(),
                        'credit_sale_list'=> $sales->where('bill_type', 1),
                        'print_date' => $print_date,


                        'total_jobcards'  => $jobcards->count(),
                        'total_jobcards_pending_completion'  => $jobcards->where('jobcard_status',0)->count(),
                        'total_jobcards_pending_delivery'  => $jobcards->where('jobcard_status',1)->count(),
                        'total_jobcards_delivered'  => $jobcards->where('jobcard_status',2)->count(),
                        'total_jobcard_amount'  => $jobcards->sum('pay_amount'),
                        'total_jobcard_received_amount'  => $jobcard_payments->sum('amount'),
                        'jobcard_item_details' => $jobcard_items,

                        'total_cash_jobcards_received' => $jobcard_payments->where('pay_type', 'Cash')->sum('amount'),
                        'total_bank_jobcards_received' => $jobcard_payments->where('pay_type', 'Bank')->sum('amount'),



                    ];
                    return $details;
                }
            }

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseLogController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseReportsController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RewardSaleController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\JobcardController;
use App\Http\Controllers\LoginPageController;
use App\Http\Controllers\JobcardPaymentController;
use App\Http\Controllers\PurchasePaymentController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ClosingController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\VendorSaleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();




Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




Route::get('/', function () {
    return redirect()->route('login');
});


// Route::group(['prefix' => 'superadmin', 'middleware' => 'auth'], function () {
//     Route::get('dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
// });
// Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin', 'auth', 'preventBackHistory']], function () {


Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin', 'auth', 'preventBackHistory']], function () {

    Route::post('/user-register', [RegisterController::class, 'register']);
     Route::get('/get-users', [RegisterController::class, 'getUsers']);
     Route::post('/delete-user', [RegisterController::class, 'deleteUser']);
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');


    Route::post('/add-customer', [CustomerController::class, 'save']);
    Route::get('/get-customers', [CustomerController::class, 'getCustomers']);


    Route::post('/delete-customer', [CustomerController::class, 'deleteCustomer']);

    Route::post('/add-product', [ProductController::class, 'save']);
    Route::get('/get-products', [ProductController::class, 'getProducts']);
    Route::post('/delete-product', [ProductController::class, 'deleteProduct']);

    Route::get('/get-stock-report', [ProductController::class, 'getStockProducts']);
    Route::get('/get-service-product', [ProductController::class, 'getServiceProducts']);
    Route::get('/get-sale-product', [ProductController::class, 'getSaleProducts']);
    Route::get('/product-categories', [CategoryController::class, 'getCategory']);


    Route::post('/add-sale', [SaleController::class, 'save']);
    Route::get('/get-sales', [SaleController::class, 'getSales']);

    //vendor-sale
    Route::post('/add-vendor-sale', [VendorSaleController::class, 'save']);
    Route::get('/get-vendor-sales', [VendorSaleController::class, 'getVendorSales']);

    Route::get('/get-today-sales', [SaleController::class, 'getTodaySales']);
    Route::post('/delete-sale', [SaleController::class, 'deleteSale']);

    Route::post('/add-quotation', [QuotationController::class, 'save']);
    Route::get('/get-quotations', [QuotationController::class, 'getQuotations']);
    Route::post('/delete-quotation', [QuotationController::class, 'deleteQuotation']);

    Route::post('/add-purchase', [PurchaseLogController::class, 'save']);
    Route::get('/get-purchases', [PurchaseLogController::class, 'getPurchases']);
    Route::get('/get-today-purchases', [PurchaseLogController::class, 'getTodayPurchases']);

    Route::post('/delete-purchase', [PurchaseLogController::class, 'deletePurchase']);

    Route::post('/add-purchase-order', [PurchaseOrderController::class, 'save']);
    Route::get('/get-purchase-orders', [PurchaseOrderController::class, 'getPurchaseOrders']);
    Route::post('/delete-purchase-order', [PurchaseOrderController::class, 'deletePurchaseOrder']);
    Route::get('/get-items-by-vendor', [PurchaseOrderController::class, 'getItemsByVendor']);


    Route::post('/add-payment', [PaymentController::class, 'save']);
    Route::get('/get-payments', [PaymentController::class, 'getPayments']);
    Route::post('/delete-payment', [PaymentController::class, 'deletePayment']);
    Route::post('/mark-bad-debt', [PaymentController::class, 'markBadDebt']);

    Route::post('/add-adjustment', [AdjustmentController::class, 'save']);
    Route::get('/get-adjustments', [AdjustmentController::class, 'getAdjustments']);
    Route::post('/delete-adjustment', [AdjustmentController::class, 'deleteAdjustment']);

    Route::post('/add-rewardsale', [RewardSaleController::class, 'save']);
    Route::get('/get-rewardsales', [RewardSaleController::class, 'getRewardSales']);
    Route::post('/delete-rewardsale', [RewardSaleController::class, 'deleteRewardSale']);

    Route::get('/get-credits', [ReportController::class, 'consolidateCreditReport']);
    Route::post('/get-credits-by-customer', [ReportController::class, 'creditReportByCustomer']);
    Route::post('/get-creditsales-by-customer', [ReportController::class, 'getCreditSalesByInvoice']);

    Route::post('/add-expense-category', [ExpenseCategoryController::class, 'save']);
    Route::get('/get-expense-categories', [ExpenseCategoryController::class, 'getExpenseCategories']);
    Route::post('/delete-expense-category', [ExpenseCategoryController::class, 'deleteExpenseCategory']);

    Route::post('/add-income-category', [IncomeCategoryController::class, 'save']);
    Route::get('/get-income-categories', [IncomeCategoryController::class, 'getIncomeCategories']);
    Route::post('/delete-income-category', [IncomeCategoryController::class, 'deleteIncomeCategory']);


    Route::post('/add-expense', [ExpenseController::class, 'save']);
    Route::get('/get-expenses', [ExpenseController::class, 'getExpenses']);
    Route::get('/get-today-expenses', [ExpenseController::class, 'getTodayExpenses']);
    Route::post('/delete-expense', [ExpenseController::class, 'deleteExpense']);

    Route::post('/add-income', [IncomeController::class, 'save']);
    Route::get('/get-incomes', [IncomeController::class, 'getIncomes']);
    Route::get('/get-today-incomes', [IncomeController::class, 'getTodayIncomes']);
    Route::post('/delete-income', [IncomeController::class, 'deleteIncome']);

    //get-designations
    Route::get('/get-designations', [DesignationController::class, 'getDesignations']);

    Route::post('/add-employee', [EmployeeController::class, 'save']);
    Route::get('/get-employees', [EmployeeController::class, 'getEmployees']);
    Route::post('/delete-employee', [EmployeeController::class, 'deleteEmployee']);
    Route::get('/get-job-adviser', [EmployeeController::class, 'getJobAdvisor']);
    Route::get('/get-technician', [EmployeeController::class, 'getTechnician']);

    Route::post('/add-jobcard', [JobcardController::class, 'save']);
    Route::get('/get-jobcards', [JobcardController::class, 'getJobcards']);
    Route::post('/delete-jobcard', [JobcardController::class, 'deleteJobcard']);
    Route::post('/mark_jobcardcompleted', [JobcardController::class, 'mark_jobcardcompleted']);
    Route::post('/mark_jobcarddelivered', [JobcardController::class, 'mark_jobcarddelivered']);
    Route::post('/back_to_list', [JobcardController::class, 'backToList']);
    Route::get('/get-payment-balance-jobcards', [JobcardController::class, 'getPaymentBalanceJobcards']);

    Route::post('jobcard_payments/get', [JobcardPaymentController::class,'get_payments']);
    Route::post('jobcard_payments/add', [JobcardPaymentController::class,'add_payment']);
    Route::get('jobcard_payments/all', [JobcardPaymentController::class,'get_all_payments']);
    Route::post('jobcard_payments/delete', [JobcardPaymentController::class,'delete_payment']);

    Route::post('/add-purchase-payment', [PurchasePaymentController::class, 'save']);
    Route::post('/get-purchase-payments', [PurchasePaymentController::class, 'getPayments']);
    Route::post('/delete-purchase-payment', [PurchasePaymentController::class, 'deletePayment']);

    Route::get('/get-rewards', [ReportController::class, 'consolidateRewardReport']);
    Route::post('/get-rewards-by-customer', [ReportController::class, 'rewardReportByCustomer']);

    Route::post('/add-vendor', [VendorController::class, 'save']);
    Route::get('/get-vendors', [VendorController::class, 'getVendors']);
    Route::post('/delete-vendor', [VendorController::class, 'deleteVendor']);

    Route::post('/add-sale-return', [SaleReturnController::class, 'save']);
    Route::get('/get-sale-returns', [SaleReturnController::class, 'getSaleReturns']);
    Route::post('/delete-sale-return', [SaleReturnController::class, 'deleteSaleReturn']);
    Route::get('/get-sale-return-by-invoice', [SaleReturnController::class, 'getSaleReturnByInvoice']);

    Route::post('/add-opening', [ClosingController::class, 'addOpening']);
    Route::post('/add-closing', [ClosingController::class, 'closeAccount']);
    Route::get('/get-closings', [ClosingController::class, 'getClosing']);
    Route::get('/get-all-closings', [ClosingController::class, 'getAllClosing']);

    Route::get('/get-purchase-credits', [ReportController::class, 'consolidatePurchaseCreditReport']);

    Route::get('/get-sale-by-invoice', [SaleController::class, 'getSaleByInvoice']);

    Route::post('/get-item-details', [ReportController::class, 'itemReport']);

    Route::post('/get-dashboard', [DashboardController::class, 'report']);


    Route::post('/get-company', [CompanyController::class, 'getCompany']);

    Route::get('/get-profit-report', [ReportController::class, 'profitReport']);

    //Change password
    Route::post('updatePassword', [PasswordChangeController::class, 'changePassword']);
// // dompdf
// Route::get('/get-pdf', [ReportController::class, 'pdf']);

    Route::post('/send-invoice-mail', [ReportController::class, 'invoiceSendData']);

});

Route::get('/get-invoice', [ReportController::class, 'pdfInvoice']);
Route::get('/get-quotation', [ReportController::class, 'pdfQuotation']);

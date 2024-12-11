<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BankaccController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome'); // welcome page for website
    // redirect to login page, when just enter the domain name
    return redirect('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/category', [InventoryController::class, 'category'])->name('category');
    Route::get('/add_category', [InventoryController::class, 'add_category'])->name('add-category');
    Route::post('/save_category', [InventoryController::class, 'save_category'])->name('save-category');
    
    Route::get('/add_item', [InventoryController::class, 'add_item'])->name('add-item');
    Route::post('/save_item', [InventoryController::class, 'save_item'])->name('save-item');
    Route::get('/products', [InventoryController::class, 'products'])->name('products');
    Route::get('autocomplete_product_search', [InventoryController::class, 'autocomplete_product_search'])->name('autocomplete_product_search');
    
    Route::get('/sales', [SalesController::class, 'index'])->name('sales');
    Route::post('/save_sales', [SalesController::class, 'set_sales'])->name('save-sales');
    Route::get('/sales_invoice/{id}', [SalesController::class, 'invoice'])->name('sales-invoice');
    Route::get('/sales_trnx_edit/{id}', [SalesController::class, 'sales_edit'])->name('sales-trnx-edit');
    Route::get('/sales_trnx_delete/{id}', [SalesController::class, 'sales_delete'])->name('sales-trnx-delete');

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
    Route::get('/add_new_customer', [CustomerController::class, 'open_customer_form'])->name('add-new-customer');
    Route::post('/save_customer', [CustomerController::class, 'set_customer'])->name('save-customer');
    Route::get('/edit_customer/{id}', [CustomerController::class, 'edit_customer'])->name('edit-customer');
    Route::post('/update_customer/{id}', [CustomerController::class, 'update_customer'])->name('update-customer');
    Route::get('/delete_customer/{id}', [CustomerController::class, 'delete_customer'])->name('delete-customer');
    Route::get('/customer_details/{id}', [CustomerController::class, 'see_customer'])->name('customer-details');
    Route::post('/save_customer_amount', [CustomerController::class, 'add_amount'])->name('save-customer-amount');
    Route::get('/customer_trnx_edit/{id}', [CustomerController::class, 'customer_tnx_edit'])->name('customer-trnx-edit');
    Route::get('/customer_trnx_delete/{id}', [CustomerController::class, 'customer_tnx_delete'])->name('customer-trnx-delete');
    
    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase');
    Route::post('/save_purchase', [PurchaseController::class, 'set_purchase'])->name('save-purchase');
    Route::get('/purchase_invoice/{id}', [PurchaseController::class, 'invoice'])->name('purchase-invoice');
    Route::get('/purchase_trnx_edit/{id}', [PurchaseController::class, 'purchase_edit'])->name('purchase-trnx-edit');
    Route::get('/purchase_trnx_delete/{id}', [PurchaseController::class, 'purchase_delete'])->name('purchase-trnx-delete');

    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor');
    Route::get('/add_new_vendor', [VendorController::class, 'open_vendor_form'])->name('add-new-vendor');
    Route::post('/save_vendor', [VendorController::class, 'set_vendor'])->name('save-vendor');
    Route::get('/edit_vendor/{id}', [VendorController::class, 'edit_vendor'])->name('edit-vendor');
    Route::post('/update_vendor/{id}', [VendorController::class, 'update_vendor'])->name('update-vendor');
    Route::get('/delete_vendor/{id}', [VendorController::class, 'delete_vendor'])->name('delete-vendor');
    Route::get('/vendor_details/{id}', [VendorController::class, 'see_vendor'])->name('vendor-details');
    Route::post('/save_vendor_amount', [VendorController::class, 'add_amount'])->name('save-vendor-amount');
    Route::get('/vendor_trnx_edit/{id}', [VendorController::class, 'vendor_tnx_edit'])->name('vendor-trnx-edit');
    Route::get('/vendor_trnx_delete/{id}', [VendorController::class, 'vendor_tnx_delete'])->name('vendor-trnx-delete');

    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee');
    Route::get('/add_new_employee', [EmployeeController::class, 'open_employee_form'])->name('add-new-employee');
    Route::post('/save_employee', [EmployeeController::class, 'set_employee'])->name('save-employee');
    Route::get('/edit_employee/{id}', [EmployeeController::class, 'edit_employee'])->name('edit-employee');
    Route::post('/update_employee/{id}', [EmployeeController::class, 'update_employee'])->name('update-employee');
    Route::get('/delete_employee/{id}', [EmployeeController::class, 'delete_employee'])->name('delete-employee');
    Route::get('/employee_details/{id}', [EmployeeController::class, 'see_employee'])->name('employee-details');
    Route::post('/save_employee_amount', [EmployeeController::class, 'add_amount'])->name('save-employee-amount');
    Route::get('/attendance', [EmployeeController::class, 'attendance'])->name('attendance');
    Route::post('/save_attendance', [EmployeeController::class, 'save_attendance'])->name('save-attendance');
    Route::get('/emp_report', [EmployeeController::class, 'attendance_report'])->name('emp-report');
    Route::get('/employee_trnx_edit/{id}', [EmployeeController::class, 'employee_trnx_edit'])->name('employee-trnx-edit');
    Route::get('/employee_trnx_delete/{id}', [EmployeeController::class, 'employee_trnx_delete'])->name('employee-trnx-delete');

    Route::get('/expense', [ExpenseController::class, 'index'])->name('expense');
    Route::get('/add_new_expense', [ExpenseController::class, 'open_expense_form'])->name('add-new-expense');
    Route::post('/save_expense', [ExpenseController::class, 'set_expense'])->name('save-expense');
    Route::get('/expense_invoice/{id}', [ExpenseController::class, 'invoice'])->name('expense-invoice');
    Route::get('/edit_expense/{id}', [ExpenseController::class, 'edit_expense'])->name('edit-expense');
    Route::post('/update_expense/{id}', [ExpenseController::class, 'update_expense'])->name('update-expense');
    Route::get('/delete_expense/{id}', [ExpenseController::class, 'delete_expense'])->name('delete-expense');
    Route::get('/expense_details/{id}', [ExpenseController::class, 'expense_details'])->name('expense-details');
    Route::post('/save_expense_amount', [ExpenseController::class, 'add_expense_amount'])->name('save-expense-amount');
    Route::get('/expense_trnx_edit/{id}', [ExpenseController::class, 'expense_edit'])->name('expense-trnx-edit');
    Route::get('/expense_trnx_delete/{id}', [ExpenseController::class, 'expense_delete'])->name('expense-trnx-delete');

    Route::get('/bank_account', [BankaccController::class, 'index'])->name('bank_account');
    Route::get('/add_new_account', [BankaccController::class, 'open_account_form'])->name('add-new-account');
    Route::post('/save_account', [BankaccController::class, 'set_account'])->name('save-account');
    Route::get('/edit_account/{id}', [BankaccController::class, 'edit_account'])->name('edit-account');
    Route::post('/update_account/{id}', [BankaccController::class, 'update_account'])->name('update-account');
    Route::get('/delete_account/{id}', [BankaccController::class, 'delete_account'])->name('delete-account');
    Route::get('/account_details/{id}', [BankaccController::class, 'acc_details'])->name('account-details');
    Route::post('/save_amount', [BankaccController::class, 'add_amount'])->name('save-amount');
    Route::get('/fund_transfer', [BankaccController::class, 'fund_transfer_form'])->name('fund-transfer');
    Route::post('/fund_transfering', [BankaccController::class, 'transfering'])->name('fund-transfering');
    Route::get('/fund_transfering_action/{type}/{id}', [BankaccController::class, 'transfer_action'])->name('fund-transfer-action');
    
    Route::get('/reportSales', [ReportController::class, 'sales'])->name('sales-report');
    Route::get('/reportTotalDue', [ReportController::class, 'total_due'])->name('total-due');
    Route::get('/reportPurchase', [ReportController::class, 'purchase'])->name('purchase-report');
    Route::get('/reportExpense', [ReportController::class, 'expense'])->name('expense-report');
    Route::get('/reportProfitAndLoss', [ReportController::class, 'profit_and_loss'])->name('profit-and-loss');
    

    Route::get('/language', [SettingsController::class, 'language'])->name('language');
    Route::post('/update_language', [SettingsController::class, 'language_change'])->name('update-language');

    Route::get('/user_manage', [SettingsController::class, 'user_manage'])->name('user-manage');
    Route::get('/add_new_user', [SettingsController::class, 'open_user_form'])->name('add-new-user');
    Route::post('/save_user', [SettingsController::class, 'set_user'])->name('save-user');
    Route::get('/edit_user/{id}', [SettingsController::class, 'edit_user'])->name('edit-user');
    Route::post('/update_user/{id}', [SettingsController::class, 'update_user'])->name('update-user');
    Route::get('/delete_user/{id}', [SettingsController::class, 'delete_user'])->name('delete-user');
    Route::get('/user_details/{id}', [SettingsController::class, 'see_user'])->name('user-details');
    Route::post('/update_user_manage', [SettingsController::class, 'user_manage_update'])->name('update-user-manage');

    Route::get('/role_manage', [SettingsController::class, 'role_manage'])->name('role-manage');
    Route::get('/add_new_role', [SettingsController::class, 'open_role_form'])->name('add-new-role');
    Route::post('/save_role', [SettingsController::class, 'set_role'])->name('save-role');
    Route::get('/edit_role/{id}', [SettingsController::class, 'edit_role'])->name('edit-role');
    Route::post('/delete_role/{id}', [SettingsController::class, 'delete_role'])->name('delete-role');
    
    Route::get('/sms', [SettingsController::class, 'sms'])->name('sms');
    Route::get('/fiscal_year', [SettingsController::class, 'fiscal_year'])->name('fy');
    Route::get('/add_fiscal_year', [SettingsController::class, 'open_fy_form'])->name('new-fy');
    Route::post('/save_fiscal_year', [SettingsController::class, 'set_fy'])->name('save-fy');
    
});

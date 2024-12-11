<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin._head')
    <style>
        .submodule{
            margin-left: 50px;
        }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      @include('admin._navbar')
      <div class="container-fluid page-body-wrapper">
        @include('admin._sidebar')
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                  <h3 class="page-title"> {{ __('admin.role_management') }} </h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('role-manage')}}" class="btn btn-sm btn-rounded btn-secondary">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" method="POST" action="{{ route('save-role') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputName1">{{ __('admin.name') }}</label>
                                        <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="{{ __('admin.name') }}">
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputName5">{{ __('admin.module_list') }}</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Inventory"> {{__('Inventory')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Inventory_Edit"> {{__('Inventory Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Sales"> {{__('Sales')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="All_Sales"> {{__('All Sales')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Sales_Add"> {{__('Sales Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Sales_Edit"> {{__('Sales Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Sales_Delete"> {{__('Sales Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Customer"> {{__('Customer')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Customer_Add"> {{__('Customer Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Customer_Details"> {{__('Customer Details')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Customer_Edit"> {{__('Customer Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Customer_Delete"> {{__('Customer Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Customer_Transection_Add"> {{__('Customer Transection Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Customer_Transection_Edit"> {{__('Customer Transection Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Customer_Transection_Delete"> {{__('Customer Transection Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Purchase"> {{__('Purchase')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="All_Purchase"> {{__('All Purchase')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Purchase_Add"> {{__('Purchase Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Purchase_Edit"> {{__('Purchase Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Purchase_Delete"> {{__('Purchase Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Vendor"> {{__('Vendor')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Vendor_Add"> {{__('Vendor Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Vendor_Details"> {{__('Vendor Details')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Vendor_Edit"> {{__('Vendor Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Vendor_Delete"> {{__('Vendor Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Vendor_Transection_Add"> {{__('Vendor Transection Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Vendor_Transection_Edit"> {{__('Vendor Transection Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Vendor_Transection_Delete"> {{__('Vendor Transection Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Settings"> {{__('Settings')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Language_Settings"> {{__('Language Settings')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="User_Settings"> {{__('User Settings')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Role_Settings"> {{__('Role Settings')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Fiscal_Year"> {{__('Fiscal Year')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Fiscal_Year_Add"> {{__('Fiscal Year Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Fiscal_Year_Delete"> {{__('Fiscal Year Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Employee"> {{__('Employee')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Details"> {{__('Employee Details')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Add"> {{__('Employee Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Edit"> {{__('Employee Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Delete"> {{__('Employee Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Transection_Add"> {{__('Employee Transection Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Transection_Edit"> {{__('Employee Transection Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Transection_Delete"> {{__('Employee Transection Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Attendance"> {{__('Employee Attendance')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Employee_Report"> {{__('Employee Report')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Expense"> {{__('Expense')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Add"> {{__('Expense Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Details"> {{__('Expense Details')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Edit"> {{__('Expense Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Delete"> {{__('Expense Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Transection_Add"> {{__('Expense Transection Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Transection_Edit"> {{__('Expense Transection Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Transection_Delete"> {{__('Expense Transection Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Accounts"> {{__('Accounts')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Accounts_Add"> {{__('Accounts Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Accounts_Details"> {{__('Accounts Details')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Accounts_Edit"> {{__('Accounts Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Accounts_Delete"> {{__('Accounts Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Accounts_Transection_Add"> {{__('Accounts Transection Add')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Accounts_Transection_Edit"> {{__('Accounts Transection Edit')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Accounts_Transection_Delete"> {{__('Accounts Transection Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Fund_Transfer"> {{__('Fund Transfer')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Fund_Transfer_Issue"> {{__('Fund Transfer Issue')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Fund_Transfer_Accept"> {{__('Fund Transfer Accept')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Fund_Transfer_Delete"> {{__('Fund Transfer Delete')}} <i class="input-helper"></i></label>
                                                </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="module[]" value="Report"> {{__('Report')}} <i class="input-helper"></i></label>
                                            </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Sales_Report"> {{__('Sales_Report')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Purchase_Report"> {{__('Purchase_Report')}} <i class="input-helper"></i></label>
                                                </div>
                                                <div class="form-check submodule">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="module[]" value="Expense_Report"> {{__('Expense_Report')}} <i class="input-helper"></i></label>
                                                </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-rounded btn-primary btn-sm me-2">{{ __('admin.save_now') }}</button><br><br>
                                    <a onclick="history.back()" class="btn btn-sm btn-rounded btn-secondary">{{ __('admin.cancel') }}</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          @include('admin._footer')
        </div>
      </div>
    </div>
    @include('admin._script')
  </body>
</html>
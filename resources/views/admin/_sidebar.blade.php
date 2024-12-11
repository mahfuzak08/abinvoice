<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
          <span class="login-status online"></span>
          <!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold name-row mb-2">{{ Auth::user()->name }}</span>
          <span class="text-secondary text-small">{{ Auth::user()->role->name }}</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard') }}">
        <span class="menu-title">{{ __('admin.dashboard') }}</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    @if(hasModuleAccess("Inventory"))
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-inventory" aria-expanded="false" aria-controls="ui-inventory">
        <span class="menu-title">{{__('admin.inventory')}}</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-codepen menu-icon"></i>
      </a>
      <div class="collapse" id="ui-inventory">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{ route('category') }}">{{__('admin.category')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('add-item') }}">{{__('admin.add_product')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{ route('products') }}">{{__('admin.all_products')}}</a></li>
          {{-- <li class="nav-item"> <a class="nav-link" href="">{{__('admin.barcode')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="">{{__('admin.reorder')}}</a></li> --}}
        </ul>
      </div>
    </li>
    @endif
    @if(hasModuleAccess("Sales"))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('sales') }}">
        <span class="menu-title">{{ __('admin.sales') }}</span>
        <i class="mdi mdi-cart-off menu-icon"></i>
      </a>
    </li>
    @endif
    @if(hasModuleAccess("Customer"))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('customer') }}">
        <span class="menu-title">{{ __('admin.customer') }}</span>
        <i class="mdi mdi-account-multiple menu-icon"></i>
      </a>
    </li>
    @endif
    @if(hasModuleAccess("Purchase"))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('purchase') }}">
        <span class="menu-title">{{ __('admin.purchase') }}</span>
        <i class="mdi mdi-cart-plus menu-icon"></i>
      </a>
    </li>
    @endif
    @if(hasModuleAccess("Vendor"))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('vendor') }}">
        <span class="menu-title">{{ __('admin.vendor') }}</span>
        <i class="mdi mdi-account-multiple-outline menu-icon"></i>
      </a>
    </li>
    @endif
    @if(hasModuleAccess("Employee"))
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-employee" aria-expanded="false" aria-controls="ui-employee">
        <span class="menu-title">{{__('admin.employee')}}</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account-check menu-icon"></i>
      </a>
      <div class="collapse" id="ui-employee">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('employee')}}">{{__('admin.employee')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('attendance')}}">{{__('admin.attendance')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('emp-report')}}">{{__('admin.report')}}</a></li>
        </ul>
      </div>
    </li>
    @endif
    @if(hasModuleAccess("Expense"))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('expense') }}">
        <span class="menu-title">{{ __('admin.expense') }}</span>
        <i class="mdi mdi-cards-outline menu-icon"></i>
      </a>
    </li>
    @endif
    @if(hasModuleAccess("Accounts"))
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#accounts" aria-expanded="false" aria-controls="report">
        <span class="menu-title">{{__('admin.accounts')}}</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-bank menu-icon"></i>
      </a>
      <div class="collapse" id="accounts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('bank_account')}}">{{__('admin.account_details')}}</a></li>
          @if(hasModuleAccess("Fund_Transfer"))
            <li class="nav-item"> <a class="nav-link" href="{{route('fund-transfer')}}">{{__('admin.fund_transfer')}}</a></li>
          @endif
        </ul>
      </div>
    </li>
    @endif
    @if(hasModuleAccess("Report"))
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#report" aria-expanded="false" aria-controls="report">
        <span class="menu-title">{{__('admin.report')}}</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-file-document menu-icon"></i>
      </a>
      <div class="collapse" id="report">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('sales-report')}}">{{__('admin.sales')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('purchase-report')}}">{{__('admin.purchase')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('expense-report')}}">{{__('admin.expense')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('profit-and-loss')}}">{{__('admin.profit_and_loss')}}</a></li>
        </ul>
      </div>
    </li>
    @endif
    @if(hasModuleAccess("Settings"))
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">{{__('admin.settings')}}</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-crosshairs-gps menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('language')}}">{{__('admin.language')}}</a></li>
          @if(Auth::user()->role->name == 'Super Admin')
          <li class="nav-item"> <a class="nav-link" href="{{route('user-manage')}}">{{__('admin.user_management')}}</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('role-manage')}}">{{__('admin.role_management')}}</a></li>
          @endif
          <li class="nav-item"> <a class="nav-link" href="{{route('sms')}}">{{__('admin.sms_manage')}}</a></li>
          @if(hasModuleAccess("Fiscal_Year"))
          <li class="nav-item"> <a class="nav-link" href="{{route('fy')}}">{{__('admin.fy')}}</a></li>
          @endif
        </ul>
      </div>
    </li>
    @endif
    <li class="nav-item">
      <form method="POST" action="{{ route('logout') }}" x-data>
        @csrf
        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
            {{ __('admin.logout') }}
        </x-responsive-nav-link>
      </form>
    </li>
  </ul>
</nav>
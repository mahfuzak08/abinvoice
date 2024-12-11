<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo" style="margin-left: 30px;float: left;" href="{{ route('dashboard') }}">
      <img src="pollifeed-logo-mini.png" style="width: 36px;height: 36px;float: left;top: 15px;position: absolute;" alt="{{config('app.name')}} logo" /> 
      <div style="font-size: 20px;font-weight: bold;">{{config('app.name')}} </div>
    </a>
    <a class="navbar-brand brand-logo-mini" style="margin-left: 15px;float: left;" href="{{ route('dashboard') }}">
      <img src="pollifeed-logo-mini.png" style="width: 36px;height: 36px;float: left;top: 15px;position: absolute;" alt="{{config('app.name')}} logo" /> 
      <div class="logo-mini-title" style="font-size: 24px;position: relative;margin: 100%;">{{config('app.name')}} </div>
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    <div class="search-field d-none d-md-block">
    </div>
    <ul class="navbar-nav navbar-nav-right"></ul>
    <x-app-layout>
    
    </x-app-layout>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>
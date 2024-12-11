<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin._head')
  </head>
  <body>
    <div class="container-scroller">
      @include('admin._navbar')
      <div class="container-fluid page-body-wrapper">
        @include('admin._sidebar')
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> {{ __('admin.report')}}
              </h3>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="row">
              <div class="col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                  <div class="card-body">
                    <img src="admin/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                    <h2 class="font-weight-normal mb-3" style="font-size: 50px;">{{__('admin.accounts_payable')}}</h2>
                    <h5 class="card-text">{{e2bn(number_format($data['accounts_receivable'], 2))}}</h5>
                  </div>
                </div>
              </div>
              <div class="col-md-6 stretch-card grid-margin">
                <div class="card bg-gradient-primary card-img-holder text-white">
                  <div class="card-body">
                      <img src="admin/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                      <h2 class="font-weight-normal mb-3" style="font-size: 50px;">{{__('admin.accounts_receivable')}}</h2>
                      <h5 class="card-text">{{e2bn(number_format(abs($data['accounts_payable']), 2))}}</h5>
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
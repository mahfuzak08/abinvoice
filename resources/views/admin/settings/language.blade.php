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
                    </span> {{ __('admin.select_your_language')}}
                  </h3>
                </div>
                <div class="row">
                    <div class="col-12 col-md-8 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" method="POST" action="{{ route('update-language') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputName5">{{ __('admin.default_language') }}</label>
                                        <div class="form-check">
                                          <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="lang" id="exampleInputName51" value="bn" {{auth()->user()->lang == "bn" ? "checked='true'" : ''}}> {{__('বাংলা')}} <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-check">
                                          <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="lang" id="exampleInputName52" value="en" {{auth()->user()->lang == "en" ? "checked='true'" : ''}}> {{__('English')}} <i class="input-helper"></i></label>
                                        </div>
                                        <button type="submit" class="btn btn-rounded btn-primary btn-sm me-2">{{ __('admin.save_now') }}</button>
                                        <a onclick="history.back()" class="btn btn-sm btn-rounded btn-secondary">{{ __('admin.cancel') }}</a>
                                    </div>
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
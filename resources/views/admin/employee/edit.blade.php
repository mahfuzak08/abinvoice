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
                  <h3 class="page-title"> {{ __('admin.employee') }} </h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('employee')}}" class="btn btn-sm btn-rounded btn-secondary">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 col-md-8 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" method="POST" action="{{ route('update-employee', $employee->id) }}">
                                    @csrf
                                    <div class="form-group">
                                      <label for="exampleInputName1">{{ __('admin.name') }}</label>
                                      <input type="text" value="{{$employee->name}}" class="form-control" id="exampleInputName1" name="name" placeholder="{{ __('admin.name') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName2">{{ __('admin.mobile') }}</label>
                                        <input type="text" value="{{$employee->mobile}}" class="form-control" id="exampleInputName2" name="mobile" placeholder="{{ __('admin.mobile') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName3">{{ __('admin.gender') }}</label>
                                        <div class="form-froup row">
                                          <div class="col-sm-6">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="gender" id="exampleInputName3" value="male" {{$employee->gender == "male" ? "checked='true'" : ''}}> {{__('admin.male')}} <i class="input-helper"></i></label>
                                            </div>
                                          </div>
                                          <div class="col-sm-6">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="gender" id="exampleInputName4" value="female" {{$employee->gender == "female" ? "checked='true'" : ''}}> {{__('admin.female')}} <i class="input-helper"></i></label>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                      <label for="exampleInputName6">{{ __('admin.designation') }}</label>
                                      <input type="text" value="{{$employee->designation}}" class="form-control" id="exampleInputName6" name="designation" placeholder="{{ __('admin.designation') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName5">{{ __('admin.address') }}</label>
                                        <input type="text" value="{{$employee->address}}" class="form-control" id="exampleInputName5" name="address" placeholder="{{ __('admin.address') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName7">{{ __('admin.nid') }}</label>
                                        <input type="text" value="{{$employee->nid}}" class="form-control" id="exampleInputName7" name="nid" placeholder="{{ __('admin.nid') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName8">{{ __('admin.salary') }}</label>
                                        <input type="number" value="{{$employee->salary}}" class="form-control" id="exampleInputName8" name="salary" placeholder="{{ __('admin.salary') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName9">{{ __('admin.bonus') }}</label>
                                        <input type="number" value="{{$employee->bonus}}" class="form-control" id="exampleInputName8" name="bonus" placeholder="{{ __('admin.bonus') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName10">{{ __('admin.emp_type') }}</label>
                                        <div class="form-froup row">
                                          <div class="col-sm-6">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="emp_type" id="exampleInputName10" value="Permanent" {{$employee->emp_type == "Permanent" ? "checked='true'" : ''}}> {{__('admin.permanent')}} <i class="input-helper"></i></label>
                                            </div>
                                          </div>
                                          <div class="col-sm-6">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="emp_type" id="exampleInputName11" value="Contractual" {{$employee->emp_type == "Contractual" ? "checked='true'" : ''}}> {{__('admin.contractual')}} <i class="input-helper"></i></label>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName12">{{ __('admin.joining') }}</label>
                                        <input type="date" value="{{$employee->joining}}" class="form-control" id="exampleInputName12" name="joining" placeholder="{{ __('admin.joining') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName13">{{ __('admin.closing') }}</label>
                                        <input type="date" value="{{$employee->closing}}" class="form-control" id="exampleInputName13" name="closing" placeholder="{{ __('admin.closing') }}">
                                    </div>
                              
                                    <button type="submit" class="btn btn-rounded btn-primary btn-sm me-2">{{ __('admin.update') }}</button><br><br>
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
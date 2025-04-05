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
                  <h3 class="page-title"> {{ __('admin.ticket') }} </h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a onclick="history.back()" class="btn btn-sm btn-rounded btn-secondary">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" method="POST" enctype="multipart/form-data" action="{{ route('save-ticket') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputName2">{{ __('admin.customer') }} <span class="text-danger">*</span></label>
                                        <select class="form-select" id="exampleInputName2" name="customer_id">
                                          <option>Select a customer</option>
                                          @foreach($customers as $row)
                                          <option value="{{$row->id}}">{{$row->name}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1">{{ __('admin.title') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="exampleInputName1" name="title" placeholder="{{ __('admin.title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName5">{{ __('admin.priority') }} <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                          <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="priority" id="exampleInputName51" value="Very High"> {{__('Very High')}} <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-check">
                                          <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="priority" id="exampleInputName52" value="High"> {{__('High')}} <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-check">
                                          <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="priority" id="exampleInputName53" value="Normal" checked="true"> {{__('Normal')}} <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-check">
                                          <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="priority" id="exampleInputName53" value="Low"> {{__('Low')}} <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName6">{{ __('admin.description') }}</label>
                                        <input type="text" class="form-control" id="exampleInputName6" name="description" placeholder="{{ __('admin.description') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName7">{{ __('admin.attchment') }}</label>
                                        <input type="file" class="form-control" id="exampleInputName7" name="img[]" multiple>
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
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
                  <h3 class="page-title"> {{ __('admin.contact') }} </h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('contacts')}}" class="btn btn-sm btn-rounded btn-secondary">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" method="POST" action="{{ route('save-contact') }}">
                                  @csrf
                                  <input type="hidden" name="id" value="{{$contact[0]->id}}">
                                  <div class="form-group">
                                      <label for="exampleInputName1">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="exampleInputName1" name="name" value="{{$contact[0]->name}}" placeholder="{{ __('admin.name') }}">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputName6">{{ __('admin.mobile') }}</label>
                                      <input type="text" class="form-control" id="exampleInputName6" name="mobile" value="{{$contact[0]->mobile}}" placeholder="{{ __('admin.mobile') }}">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputName4">{{ __('admin.email') }}</label>
                                      <input type="text" class="form-control" id="exampleInputName4" name="email" value="{{$contact[0]->email}}" placeholder="{{ __('admin.email') }}">
                                  </div>
                                  @if($contact[0]->img)
                                    <div class="row">
                                      @php
                                      $img = str_replace("public", "storage", $contact[0]->img);
                                      @endphp
                                      <div class="col-3">
                                        <a href="{{ $img }}" target="_blank"><img src="{{ $img }}" ></a>
                                      </div>
                                    </div>
                                  @endif
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
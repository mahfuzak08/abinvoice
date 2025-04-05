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
                      <li class="breadcrumb-item"><a href="{{route('tickets')}}" class="btn btn-sm btn-rounded btn-secondary">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" method="POST" action="{{ route('save-ticket') }}">
                                  @csrf
                                  <input type="hidden" name="id" value="{{$ticket[0]->id}}">
                                  <div class="form-group">
                                      <label for="exampleInputName2">{{ __('admin.customer') }} <span class="text-danger">*</span></label>
                                      <select class="form-select" id="exampleInputName2" name="customer_id">
                                        <option>Select a customer</option>
                                        @foreach($customers as $row)
                                        <option value="{{$row->id}}" {{$ticket[0]->client_id == $row->id ? 'selected' : ''}}>{{$row->name}}</option>
                                        @endforeach
                                      </select>
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputName1">{{ __('admin.status') }} <span class="text-danger">*</span></label>
                                      <select class="form-select" id="exampleInputName1" name="status">
                                        <option>Select a status</option>
                                        @foreach($status as $row)
                                        <option value="{{$row}}" {{$ticket[0]->status == $row ? 'selected' : ''}}>{{$row}}</option>
                                        @endforeach
                                      </select>
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputName1">{{ __('admin.title') }} <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="exampleInputName1" name="title" value="{{$ticket[0]->title}}" placeholder="{{ __('admin.title') }}">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputName5">{{ __('admin.priority') }} <span class="text-danger">*</span></label>
                                      <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="priority" id="exampleInputName51" value="Very High" {{$ticket[0]->priority == 'Very High' ? 'checked' : ''}}> {{__('Very High')}} <i class="input-helper"></i></label>
                                      </div>
                                      <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="priority" id="exampleInputName52" value="High" {{$ticket[0]->priority == 'High' ? 'checked' : ''}}> {{__('High')}} <i class="input-helper"></i></label>
                                      </div>
                                      <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="priority" id="exampleInputName53" value="Normal" {{$ticket[0]->priority == 'Normal' ? 'checked' : ''}}> {{__('Normal')}} <i class="input-helper"></i></label>
                                      </div>
                                      <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="priority" id="exampleInputName53" value="Low" {{$ticket[0]->priority == 'Low' ? 'checked' : ''}}> {{__('Low')}} <i class="input-helper"></i></label>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputName6">{{ __('admin.description') }}</label>
                                      <input type="text" class="form-control" id="exampleInputName6" name="description" value="{{$ticket[0]->description}}" placeholder="{{ __('admin.description') }}">
                                  </div>
                                  @if($ticket[0]->imgs)
                                    @php 
                                    $imgs = json_decode($ticket[0]->imgs);
                                    @endphp
                                    <div class="row">
                                    @foreach($imgs as $img)
                                      @php
                                      $img = str_replace("public", "storage", $img);
                                      @endphp
                                      <div class="col-3">
                                        <a href="{{ $img }}" target="_blank"><img src="{{ $img }}" ></a>
                                      </div>
                                    @endforeach
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
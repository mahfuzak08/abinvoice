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
                  <h3 class="page-title"> {{ __('admin.category') }} </h3>
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
                                <form class="forms-sample" method="POST" action="{{ route('save-category') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$category->id}}">
                                    <div class="form-group">
                                        <label for="exampleInputName1">{{ __('admin.category_name') }}</label>
                                        <input type="text" class="form-control" id="exampleInputName1" name="name" value="{{$category->name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName5">{{ __('admin.parent_category') }}</label>
                                        <div class="form-check">
                                          <select class="form-select" name="parent" id="input9">
                                            <option value="0" {{$category->parent == 0 ? 'selected' : ''}}>No Parent</option>
                                            @foreach($categories as $row)
                                            <option value="{{$row->id}}" {{$category->parent == $row->id ? 'selected' : ''}}>{{$row->name}}</option>
                                            @endforeach
                                          </select>
                                        </div>
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
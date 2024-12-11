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
                  <h3 class="page-title">{{__('admin.vendor')}}</h3>
                  @if(hasModuleAccess('Vendor_Add'))
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('add-new-vendor')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
                    </ol>
                  </nav>
                  @endif
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <form action="{{route('vendor')}}" method="GET">
                                @csrf
                                @php 
                                $sv = isset($_GET['search']) ? $_GET['search'] : '';
                                @endphp
                                <div class="row">
                                  <input type="text" name="search" class="col-12 col-md-10" value="{{$sv}}" placeholder="{{__('admin.what_you_want_to_find')}}">
                                  <button type="submit" class="col-12 col-md-2 btn btn-info">{{__('admin.find')}}</button>
                                </div>
                              </form>
                              <div class="row table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th> {{__('admin.sl')}} </th>
                                            <th> {{__('admin.name')}} </th>
                                            <th> {{__('admin.mobile')}} </th>
                                            <th> {{__('admin.address')}} </th>
                                            <th> {{__('admin.balance')}} </th>
                                            <th> {{__('admin.action')}} </th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      @if(count($datas) > 0)
                                        @php 
                                        if(isset($_GET['page']) && $_GET['page']>0)
                                          $n = 1 + (($_GET['page'] - 1) * 10);
                                        else
                                          $n = 1;
                                        @endphp
                                        @foreach($datas as $row)
                                          <tr>
                                            <td>{{$n++}}</td>
                                            <td><a href="{{route('vendor-details', $row->id)}}" class="{{$row->due == 0 ? 'text-warning' : ''}}">{{$row->name}}</a></td>
                                            <td>{{e2bn($row->mobile)}}</td>
                                            <td>{{$row->address}}</td>
                                            <td>{{e2bn(number_format($row->due, 2))}}</td>
                                            <td>
                                              <a href="{{route('vendor-details', $row->id)}}" class="btn btn-info btn-rounded btn-sm">{{__('admin.details')}}</a> 
                                              @if(hasModuleAccess('Vendor_Edit'))
                                                <a href="{{route('edit-vendor', $row->id)}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.edit')}}</a> 
                                              @endif
                                              @if(hasModuleAccess('Vendor_Delete'))
                                                <a href="{{route('delete-vendor', $row->id)}}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a> 
                                              @endif
                                            </td>
                                          </tr>
                                        @endforeach
                                      @else
                                          <tr>
                                            <td colspan="6" class="text-center">{{__('admin.no_data_found')}}</td>
                                          </tr>
                                      @endif
                                    </tbody>
                                </table>
                              </div>
                              {{ $datas->onEachSide(3)->links() }}
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
    <script>
      function openForm(){
        $('#addForm').removeClass('d-none');
      }
    </script>
  </body>
</html>
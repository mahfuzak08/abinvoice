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
                    </span> {{ __('admin.fy')}}
                  </h3>
                  @if(hasModuleAccess('Fiscal_Year_Add'))
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('new-fy')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
                    </ol>
                  </nav>
                  @endif
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <div class="row table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th> {{__('admin.sl')}} </th>
                                            <th> {{__('admin.name')}} </th>
                                            <th> {{__('admin.start_date')}} </th>
                                            <th> {{__('admin.end_date')}} </th>
                                            <th> {{__('admin.status')}} </th>
                                            <th> {{__('admin.action')}} </th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      @if(count($fys) > 0)
                                        @php 
                                        $n = 1;
                                        @endphp
                                        @foreach($fys as $row)
                                          <tr>
                                            <td>{{$n++}}</td>
                                            <td>{{$row->name}}</td>
                                            <td>{{date('d-m-Y', strtotime($row->start_date))}}</td>
                                            <td>{{date('d-m-Y', strtotime($row->end_date))}}</td>
                                            <td>{{$row->is_active == 'yes' ? 'Active' : 'Closed'}}</td>
                                            <td>
                                              @if(hasModuleAccess('Fiscal_Year_Delete'))
                                                @if($row->is_active == 'no')
                                                <a href="{{ URL::route('fy', ['id' => $row->id, 'type' => 'active']) }}" class="btn btn-warning btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to active this year?')">{{__('admin.active')}}</a> 
                                                @endif
                                                {{-- <a href="{{route('edit-fy', $row->id)}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.edit')}}</a>  --}}
                                                <a href="{{ URL::route('fy', ['id' => $row->id, 'type' => 'delete']) }}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a> 
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
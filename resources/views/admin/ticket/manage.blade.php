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
                  <h3 class="page-title"> {{__('admin.ticket')}} </h3>
                  @if(hasModuleAccess('Tickets_Add'))
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{ URL::route('tickets', ['id' => 'new']) }}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
                    </ol>
                  </nav>
                  @endif
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th> {{__('admin.sl')}} </th>
                                <th> {{__('admin.date')}} </th>
                                <th> {{__('admin.title')}} </th>
                                <th> {{__('admin.customer')}} </th>
                                <th> {{__('admin.priority')}} </th>
                                <th> {{__('admin.status')}} </th>
                                <th> {{__('admin.action')}} </th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(count($tickets) > 0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach ($tickets as $row)
                                  <tr>
                                    <td>{{$i++}}</td>
                                    <td> {{$row->opening_date}} </td>
                                    <td> {{$row->title}} </td>
                                    <td> {{$row->customer_name}} </td>
                                    <td> {{$row->priority}} </td>
                                    <td> {{$row->status}} </td>
                                    <td>
                                      <a href="{{ URL::route('tickets', ['id' => $row->id]) }}" class="btn btn-success btn-rounded btn-sm">{{__('admin.open')}}</a> 
                                      {{-- @if(hasModuleAccess('Tickets_Edit')) 
                                      @endif --}}
                                      @if(hasModuleAccess('Tickets_Delete'))
                                        <a href="{{ URL::route('tickets', ['delete' => $row->id]) }}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a> 
                                      @endif
                                    </td>
                                  </tr>
                                @endforeach
                              @else
                                <tr>
                                  <td colspan="7">{{__('admin.no_data_found')}}</td>
                                </tr>
                              @endif
                              
                            </tbody>
                          </table>
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
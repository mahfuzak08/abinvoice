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
                  <h3 class="page-title"> {{__('admin.contact')}} </h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{ URL::route('contacts', ['id' => 'new']) }}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th> {{__('admin.sl')}} </th>
                                <th> {{__('admin.name')}} </th>
                                <th> {{__('admin.mobile')}} </th>
                                <th> {{__('admin.email')}} </th>
                                <th> {{__('admin.action')}} </th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(count($contacts) > 0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach ($contacts as $row)
                                  <tr>
                                    <td>{{$i++}}</td>
                                    <td> {{$row->name}} </td>
                                    <td> {{$row->mobile}} </td>
                                    <td> {{$row->email}} </td>
                                    <td>
                                      <a href="{{ URL::route('contacts', ['id' => $row->id]) }}" class="btn btn-success btn-rounded btn-sm">{{__('admin.open')}}</a> 
                                      <a href="{{ URL::route('contacts', ['delete' => $row->id]) }}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a> 
                                    </td>
                                  </tr>
                                @endforeach
                              @else
                                <tr>
                                  <td colspan="5">{{__('admin.no_data_found')}}</td>
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
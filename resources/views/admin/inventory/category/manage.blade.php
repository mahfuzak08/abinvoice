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
                  <h3 class="page-title"> {{__('admin.category')}} </h3>
                  @if(hasModuleAccess('Accounts_Add'))
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('add-category')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
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
                                <th> {{__('admin.name')}} </th>
                                <th> {{__('admin.parent_category')}} </th>
                                <th> {{__('admin.action')}} </th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(count($categories) > 0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach ($categories as $row)
                                  <tr>
                                    <td>{{$i++}}</td>
                                    <td> {{$row->name}} </td>
                                    <td>
                                      @php
                                      if($row->parent>0){
                                        foreach($categories as $sc){
                                          if($sc->id == $row->parent)
                                            echo $sc->name;
                                        }
                                      } 
                                      @endphp
                                    </td>
                                    <td>
                                      <a href="{{ URL::route('add-category', ['id' => $row->id]) }}" class="btn btn-info btn-rounded btn-sm">{{__('admin.edit')}}</a>
                                    </td>
                                  </tr>
                                @endforeach
                              @else
                                <tr>
                                  <td colspan="4">{{__('admin.no_data_found')}}</td>
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
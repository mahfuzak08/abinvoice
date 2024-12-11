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
                  <h3 class="page-title"> {{__('admin.accounts')}} </h3>
                  @if(hasModuleAccess('Accounts_Add'))
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('add-new-account')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
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
                                <th> {{__('admin.account_name')}} </th>
                                <th> {{__('admin.account_type')}} </th>
                                <th> {{__('admin.bank_name')}} </th>
                                <th> {{__('admin.currency')}} </th>
                                <th> {{__('admin.action')}} </th>
                              </tr>
                            </thead>
                            <tbody>
                              @if(count($banks) > 0)
                                @php
                                $i = 1;
                                @endphp
                                @foreach ($banks as $bank)
                                  <tr>
                                    <td>{{$i++}}</td>
                                    <td> {{$bank->name}} </td>
                                    <td> {{$bank->type}} </td>
                                    <td> {{$bank->bank_name}} </td>
                                    <td> {{$bank->currency}} </td>
                                    <td>
                                      @if($bank->type != 'Due' && $bank->type != 'Discount')
                                      <a href="{{route('account-details', $bank->id)}}" class="btn btn-info btn-rounded btn-sm">{{__('admin.details')}}</a>
                                        @if(hasModuleAccess('Accounts_Edit')) 
                                          <a href="{{route('edit-account', $bank->id)}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.edit')}}</a> 
                                        @endif
                                        @if(hasModuleAccess('Accounts_Delete'))
                                          <a href="{{route('delete-account', $bank->id)}}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a> 
                                        @endif
                                      @endif
                                    </td>
                                  </tr>
                                @endforeach
                              @else
                                <tr>
                                  <td colspan="6">{{__('admin.no_data_found')}}</td>
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
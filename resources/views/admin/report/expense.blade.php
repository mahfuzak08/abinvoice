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
                  <h3 class="page-title">{{__('admin.expense')}}</h3>
                  {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('add-new-customer')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
                    </ol>
                  </nav> --}}
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <form action="{{route('expense-report')}}" method="GET">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">{{__('admin.start_date')}}</label>
                                            <div class="col-sm-9">
                                                <input type="date" value="{{date('d-m-Y')}}" class="form-control" name="start_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">{{__('admin.end_date')}}</label>
                                            <div class="col-sm-9">
                                                <input type="date" value="{{date('d-m-Y')}}" class="form-control" name="end_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">{{__('admin.expense_name')}}</label>
                                            <div class="col-sm-9">
                                                <select name="expense_type" style="width: 100%">
                                                    <option value="all">All</option>
                                                    @foreach($expense as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <button name="get_data" class="btn btn-success">Search</button>
                                        </div>
                                    </div>
                                </div>
                              </form>
                              <div class="row table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th> {{__('admin.sl')}} </th>
                                            <th> {{__('admin.date')}} </th>
                                            <th> {{__('admin.id')}} </th>
                                            <th> {{__('admin.expense_name')}} </th>
                                            <th> {{__('admin.account_name')}} </th>
                                            <th> {{__('admin.amount')}} </th>
                                            <th> {{__('admin.details')}} </th>
                                            <th> {{__('admin.action')}} </th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      @php 
                                      $page_total = 0;
                                      if(isset($_GET['page']) && $_GET['page']>0)
                                        $n = 1 + (($_GET['page'] - 1) * 10);
                                      else
                                        $n = 1;
                                      @endphp
                                      @if(count($datas) > 0)
                                        @php 
                                        if(isset($_GET['page']) && $_GET['page']>0)
                                          $n = 1 + (($_GET['page'] - 1) * 10);
                                        else
                                          $n = 1;
                                        @endphp
                                        @foreach($datas as $row)
                                          <tr>
                                            <td><a href="{{route('expense-invoice', $row->id)}}">{{$n++}}</a></td>
                                            <td><a href="{{route('expense-invoice', $row->id)}}">{{date('d-m-Y', strtotime($row->trnx_date))}}</a></td>
                                            <td><a href="{{route('expense-invoice', $row->id)}}">{{$row->id}}</a</td>
                                            <td><a href="{{route('expense-invoice', $row->id)}}">{{$row->expense_name}}</a></td>
                                            <td>{{$row->acc_name}}</td>
                                            <td>
                                              {{$row->amount}}
                                              @php 
                                              $page_total += $row->amount;
                                              @endphp
                                            </td>
                                            <td>{{$row->title}} {{$row->details}}</td>
                                            <td>
                                              @if(hasModuleAccess('Expense_Transection_Edit'))
                                              <a href="{{route('expense-trnx-edit', $row->id)}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.edit')}}</a> 
                                              @endif
                                              @if(hasModuleAccess('Expense_Transection_Delete'))
                                              <a href="{{route('expense-trnx-delete', $row->id)}}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a>
                                              @endif
                                            </td>
                                          </tr>
                                        @endforeach
                                      @else
                                          <tr>
                                            <td colspan="8" class="text-center">{{__('admin.no_data_found')}}</td>
                                          </tr>
                                      @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right">Page Total: </td>
                                            <td>{{number_format($page_total, 2)}}</td>
                                            <td></td>
                                        </tr>
                                        @if($etotal > 0)
                                        <tr>
                                          <td colspan="5" class="text-right">Total</td>
                                          <td>{{number_format($etotal, 2)}}</td>
                                          <td></td>
                                        </tr>
                                        @endif
                                    </tfoot>
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
  </body>
</html>
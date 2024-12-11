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
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('expense')}}" class="btn btn-sm btn-rounded btn-secondary">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              @if(hasModuleAccess('Expense_Transection_Add'))
                              <div class="row">
                                <div class="col-md-6 d-none d-md-block" id="addForm">
                                  <form class="forms-sample" method="POST" action="{{ route('save-expense-amount') }}">
                                    @csrf
                                    <input type="hidden" name="ref_id" value="{{$expense->id}}" />
                                    <input type="hidden" name="ref_type" value="expense" />
                                    <input type="hidden" name="redirect_url" value="expense_details/{{$expense->id}}" />
                                    <input type="hidden" name="type" value="deposit" />
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input1" class="col-sm-3 col-form-label">{{__('admin.name')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-border-off" disabled="true" id="input1" value="{{$expense->name}}">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input6" class="col-sm-3 col-form-label">{{__('admin.date')}}</label>
                                      <div class="col-sm-9">
                                        <input type="date" name="tranx_date" value="{{date('d-m-Y')}}" class="form-control" id="input6" required>
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input8" class="col-sm-3 col-form-label">{{__('admin.enter_your_amount')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" name="amount" placeholder="{{__('admin.enter_your_amount')}}" required class="form-control" id="input8">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input9" class="col-sm-3 col-form-label">{{__('admin.account_name')}}</label>
                                      <div class="col-sm-9">
                                        <select class="form-select" name="account_id" id="input9" aria-label="Default select example">
                                          @foreach($banks as $bank)
                                          <option value="{{$bank->id}}">{{$bank->name}}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input8" class="col-sm-3 col-form-label">{{__('admin.title')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" name="title" placeholder="{{__('admin.title')}}" class="form-control" id="input8">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input8" class="col-sm-3 col-form-label">{{__('admin.details')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" name="details" placeholder="{{__('admin.details')}}" class="form-control" id="input8">
                                      </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-2 float-end">{{ __('admin.save_now') }}</button>
                                  </form>
                                </div>
                                <div class="col-md-6 d-block d-md-none text-center">
                                  <br />
                                  <a onclick="openForm()" class="btn btn-sm btn-rounded btn-info">{{__('admin.add_new')}}</a>
                                </div>
                                <div class="col-12">
                                  <br />
                                  <hr />
                                  <br />
                                  <form action="{{route('expense-details', $expense->id)}}" method="GET">
                                    @csrf
                                    @php 
                                    $sv = isset($_GET['search']) ? $_GET['search'] : '';
                                    @endphp
                                    <div class="row">
                                      <input type="text" name="search" class="col-12 col-md-10" value="{{$sv}}" placeholder="{{__('admin.what_you_want_to_find')}}">
                                      <button type="submit" class="col-12 col-md-2 btn btn-info">{{__('admin.find')}}</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                              <br />
                              <hr />
                              @endif
                              <div class="row table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.sl')}}</th>
                                            <th>{{__('admin.date')}}</th>
                                            <th>{{__('admin.account_name')}}</th>
                                            <th>{{__('admin.details')}}</th>
                                            <th class="text-right">{{__('admin.enter_your_amount')}}</th>
                                            <th class="text-right">{{__('admin.action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @php
                                      $total = 0;
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
                                            <td>{{$n++}}</td>
                                            <td>{{date('d-m-Y', strtotime($row->trnx_date))}}</td>
                                            <td>{{$row->bank_name}}</td>
                                            <td>
                                              {{$row->title}}
                                              @if(!empty($row->title) && !empty($row->details))
                                                <br>
                                              @endif
                                              {{$row->details}}
                                            </td>
                                            <td class="text-right">{{number_format($row->amount, 2)}}</td>
                                            <td>
                                              @if(hasModuleAccess('Expense_Transection_Edit'))
                                              <a href="{{route('expense-trnx-edit', $row->id)}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.edit')}}</a> 
                                              @endif
                                              @if(hasModuleAccess('Expense_Transection_Delete'))
                                              <a href="{{route('expense-trnx-delete', $row->id)}}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a>
                                              @endif
                                            </td>
                                          </tr>
                                          @php
                                          $total += $row->amount;
                                          @endphp
                                        @endforeach
                                      @else
                                          <tr>
                                            <td colspan="6" class="text-center">{{__('admin.no_data_found')}}</td>
                                          </tr>
                                      @endif
                                    </tbody>
                                    @if($total>0)
                                    <tfoot>
                                      <tr>
                                        <td colspan="4">Page Total</td>
                                        <td class="text-right">{{number_format($total, 2)}}</td>
                                        <td></td>
                                      </tr>
                                      @if($etotal > 0)
                                      <tr>
                                        <td colspan="4">Total</td>
                                        <td class="text-right">{{number_format($etotal, 2)}}</td>
                                        <td></td>
                                      </tr>
                                      @endif
                                    </tfoot>
                                    @endif
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
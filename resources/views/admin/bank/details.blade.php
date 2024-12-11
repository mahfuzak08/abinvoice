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
                  <h3 class="page-title">{{__('admin.account_details')}}</h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('bank_account')}}" class="btn btn-sm btn-rounded btn-secondary">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="forms-sample">
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input1" class="col-sm-3 col-form-label">{{__('admin.account_name')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-border-off" disabled="true" id="input1" value="{{$bank->name}}">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input2" class="col-sm-3 col-form-label">{{__('admin.balance')}}</label>
                                      <div class="col-sm-9">
                                        <input type="email" class="form-control form-control-border-off" disabled="true" id="input2" value="{{$balance}} {{$bank->currency}}">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input3" class="col-sm-3 col-form-label">{{__('admin.bank_name')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-border-off" disabled="true" id="input3" value="{{$bank->bank_name}}">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input4" class="col-sm-3 col-form-label">{{__('admin.account_type')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-border-off" disabled="true" id="input4" value="{{$bank->type}}">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input5" class="col-sm-3 col-form-label">{{__('admin.bank_address')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-border-off" disabled="true" id="input5" value="{{$bank->bank_address}}">
                                      </div>
                                    </div>
                                    <div class="form-group form-group-margin-bottom-off row">
                                      <label for="input5" class="col-sm-3 col-form-label">{{__('admin.bank_acc_no')}}</label>
                                      <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-border-off" disabled="true" id="input5" value="{{$bank->acc_no}}">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-6 d-none d-md-block" id="addForm">
                                  @if(hasModuleAccess('Accounts_Transection_Add'))
                                    <form class="forms-sample" method="POST" action="{{ route('save-amount') }}">
                                      @csrf
                                      <input type="hidden" name="account_id" value="{{$bank->id}}" />
                                      <div class="form-group form-group-margin-bottom-off row">
                                        <label for="input6" class="col-sm-3 col-form-label">{{__('admin.date')}}</label>
                                        <div class="col-sm-9">
                                          <input type="date" name="tranx_date" class="form-control" id="input6" value="{{date('Y-m-d')}}">
                                        </div>
                                      </div>
                                      <div class="form-group form-group-margin-bottom-off row">
                                        <label for="input7" class="col-sm-3 col-form-label">{{__('admin.account_name')}}</label>
                                        <div class="col-sm-9">
                                          <input type="text" class="form-control" name="name" value="{{ $bank->name }}" disabled="true" id="input7">
                                        </div>
                                      </div>
                                      <div class="form-group form-group-margin-bottom-off row">
                                        <label for="input8" class="col-sm-3 col-form-label">{{__('admin.tranx_type')}}</label>
                                        <div class="col-sm-9">
                                          <div class="col-sm-6">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="type" id="exampleInputName51" value="deposit" checked="true"> {{__('admin.deposit')}} <i class="input-helper"></i></label>
                                            </div>
                                          </div>
                                          <div class="col-sm-6">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="type" id="exampleInputName52" value="withdrawal"> {{__('admin.withdrawal')}} <i class="input-helper"></i></label>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group form-group-margin-bottom-off row">
                                        <label for="input9" class="col-sm-3 col-form-label">{{__('admin.enter_your_amount')}}</label>
                                        <div class="col-sm-9">
                                          <input type="number" class="form-control" name="amount" value="" placeholder="{{__('admin.enter_your_amount')}}">
                                        </div>
                                      </div>
                                      <div class="form-group form-group-margin-bottom-off row">
                                        <label for="input9" class="col-sm-3 col-form-label">{{__('admin.details')}}</label>
                                        <div class="col-sm-9">
                                          <input type="text" class="form-control" name="note" value="" placeholder="{{__('admin.details')}}">
                                        </div>
                                      </div>
                                      <button type="submit" class="btn btn-primary me-2 float-end">{{ __('admin.save_now') }}</button>
                                    </form>
                                  @endif
                                </div>
                                <div class="col-md-6 d-block d-md-none text-center">
                                  <br />
                                  <a onclick="openForm()" class="btn btn-sm btn-rounded btn-info">{{__('admin.add_new')}}</a>
                                </div>
                                <div class="col-12">
                                  <br />
                                  <hr />
                                  <br />
                                  <form action="{{route('account-details', $bank->id)}}" method="GET">
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
                              
                              <div class="row table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>Ref. ID</th>
                                            <th>Ref. Type</th>
                                            <th>Note</th>
                                            <th>Amount</th>
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
                                            <td>{{date('d-m-Y', strtotime($row->tranx_date))}}</td>
                                            <td>{{$row->ref_id}}</td>
                                            <td>{{$row->ref_type}}</td>
                                            <td>{{$row->note}}</td>
                                            <td>{{$row->amount}}</td>
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
                                    <tfoot>
                                      <tr>
                                        <td colspan="5">Total</td>
                                        <td class="text-right">{{$total}}</td>
                                      </tr>
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
    <script>
      function openForm(){
        $('#addForm').removeClass('d-none');
      }
    </script>
  </body>
</html>
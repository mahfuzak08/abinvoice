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
                  <h3 class="page-title"> {{ __('admin.fund_transfer') }} </h3>
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
                                <div class="row">
                                  @if(hasModuleAccess('Fund_Transfer_Issue'))
                                    <form class="forms-sample" method="POST" action="{{ route('fund-transfering') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="exampleInputName4">{{ __('admin.date') }}</label>
                                            <input type="date" class="form-control" id="exampleInputName4" name="date">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputName1">{{ __('admin.from_acc') }}</label>
                                            <select name="from_acc" required id="from_acc" style="width: 100%;">
                                              @foreach($banks as $ac)
                                                @if($ac->type != 'Due' && $ac->type != 'Discount')
                                                <option value="{{$ac->id}}">{{$ac->name}}</option>
                                                @endif
                                              @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputName1">{{ __('admin.to_acc') }}</label>
                                            <select name="to_acc" required id="to_acc" style="width: 100%;">
                                              @foreach($banks as $ac)
                                                @if($ac->type != 'Due' && $ac->type != 'Discount')
                                                <option value="{{$ac->id}}">{{$ac->name}}</option>
                                                @endif
                                              @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputName4">{{ __('admin.amount') }}</label>
                                            <input type="number" class="form-control" id="exampleInputName4" name="amount" placeholder="{{ __('admin.amount') }}">
                                        </div>
                                  
                                        <button type="submit" class="btn btn-rounded btn-primary btn-sm me-2">{{ __('admin.send_now') }}</button><br><br>
                                        <a href="/" class="btn btn-sm btn-rounded btn-secondary">{{ __('admin.cancel') }}</a>
                                    </form>
                                  @endif
                                </div>
                                <br />
                                <hr />
                                <div class="row table-responsive">
                                  @php 
                                  $total = 0;
                                  @endphp
                                  <table class="table table-striped">
                                      <thead>
                                          <tr>
                                              <th>{{__('admin.sl')}}</th>
                                              <th>{{__('admin.date')}}</th>
                                              <th>{{__('admin.account_name')}}</th>
                                              <th>{{__('admin.details')}}</th>
                                              <th>{{__('admin.status')}}</th>
                                              <th class="text-right">{{__('admin.enter_your_amount')}}</th>
                                              <th>{{__('admin.action')}}</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        @if(count($data) > 0)
                                          @php 
                                          $ref_id = array();
                                          if(isset($_GET['page']) && $_GET['page']>0)
                                            $n = 1 + (($_GET['page'] - 1) * 10);
                                          else
                                            $n = 1;
                                          @endphp
                                          @foreach($data as $row)
                                            @if(array_search($row->ref_id, $ref_id) == false)
                                              @php 
                                              $ref_id[] = $row->ref_id;
                                              @endphp
                                              <tr>
                                                <td>{{$n++}}</td>
                                                <td>{{date('d-m-Y', strtotime($row->tranx_date))}}</td>
                                                <td>{{$row->bank_name}}</td>
                                                <td>{{$row->note}}</td>
                                                <td>{{$row->ref_tranx_type}}</td>
                                                <td class="text-right">{{number_format($row->amount, 2)}}</td>
                                                <td>
                                                    @if($row->ref_tranx_type == 'Pending')
                                                      @if(hasModuleAccess('Fund_Transfer_Accept'))
                                                        <a href="{{route('fund-transfer-action',['type' => 'accept', 'id' => $row->id])}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.accept')}}</a> 
                                                        <a href="{{route('fund-transfer-action', ['type' => 'reject', 'id' => $row->id])}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.reject')}}</a> 
                                                      @endif
                                                    @endif
                                                    @if(hasModuleAccess('Fund_Transfer_Delete'))
                                                      <a href="{{route('fund-transfer-action', ['type' => 'delete', 'id' =>$row->id])}}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a>
                                                    @endif
                                                </td>
                                              </tr>
                                              @php 
                                              $total += $row->amount;
                                              @endphp
                                            @endif
                                          @endforeach
                                        @else
                                            <tr>
                                              <td colspan="7" class="text-center">{{__('admin.no_data_found')}}</td>
                                            </tr>
                                        @endif
                                      </tbody>
                                      <tfoot>
                                        <tr>
                                          <td colspan="5">Total</td>
                                          <td class="text-right">{{number_format($total, 2)}}</td>
                                          <td></td>
                                        </tr>
                                      </tfoot>
                                  </table>
                                </div>
                                {{ $data->onEachSide(3)->links() }}
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
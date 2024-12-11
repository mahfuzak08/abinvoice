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
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <form action="{{route('profit-and-loss')}}" method="GET">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{__('admin.start_date')}}</label>
                                            <div class="col-sm-8">
                                                <input type="date" value="{{date('d-m-Y')}}" class="form-control" name="start_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">{{__('admin.end_date')}}</label>
                                            <div class="col-sm-8">
                                                <input type="date" value="{{date('d-m-Y')}}" class="form-control" name="end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <button name="get_data" class="btn btn-success">Search</button>
                                        </div>
                                    </div>
                                </div>
                              </form>
                              <div class="row table-responsive">
                                <h3 style="font-size: 24px;">{{config('app.name')}}</h3>
                                <h4 style="font-size: 20px;">{{__('admin.profit_and_loss')}}</h4>
                                <h4 style="font-size: 16px;">{{date('d-m-Y', strtotime($start_date))}} - {{date('d-m-Y', strtotime($end_date))}}</h4>
                                @php
                                $pnl = $total['sales'] - $total['purchase'] - ($total['salary']*-1);
                                $te = $total['salary'] * -1;
                                $te += $total['discount'];
                                // $tbal = 0;
                                @endphp
                                <table style="border: 1px solid #000; width: 700px;">
                                  <tr style="border: 1px solid #000; background: #999; font-weight:700;">
                                    <td style="padding: 5px 10px;">{{__('admin.Particulars')}}</td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{__('admin.amount')}}</td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{__('admin.amount')}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px 10px;">{{__('admin.sales')}}</td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($total['sales'], 2)}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px 10px;">{{__('admin.purchase')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($total['purchase'], 2)}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000; background: #CDC;">
                                    <td style="padding: 5px 10px;">{{__('admin.expense')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;"></td>
                                  </tr>
                                  @foreach($total['expense'] as $exp)
                                    @php
                                    $pnl -= $exp->total_amount;
                                    $te += $exp->total_amount;
                                    @endphp
                                    <tr style="border: 1px solid #000;">
                                      <td style="padding-left: 30px">{{$exp->expense_name}}</td>
                                      <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($exp->total_amount, 2)}}</td>
                                      <td style="text-align: right; width: 200px;"></td>
                                    </tr>
                                  @endforeach
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding-left: 30px">{{__('admin.salary')}}</td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($total['salary'] * -1, 2)}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding-left: 30px">{{__('admin.cash_discount')}}</td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($total['discount'], 2)}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px 10px;">{{__('admin.total')}} {{__('admin.expense')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($te, 2)}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000; background: #AAA;">
                                    <td style="padding: 5px 10px;">{{__('admin.net_income')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($pnl, 2)}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000; background: #CDC;">
                                    <td style="padding: 5px 10px;">{{__('admin.balance_iheet_items')}}</td>
                                    <td></td>
                                    <td></td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px 10px;">{{__('admin.accounts_receivable')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($total['sales'] - $total['receive'], 2)}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px 10px;">{{__('admin.total_payment')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($total['receive'], 2)}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px 10px;">{{__('admin.accounts_payable')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($total['purchase'] + $total['pay'], 2)}}</td>
                                  </tr>
                                  <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px 10px;">{{__('admin.quantity')}}</td>
                                    <td style="text-align: right; width: 200px;"></td>
                                    <td style="text-align: right; width: 200px;padding: 5px 10px;">{{number_format($quantity, 2)}}</td>
                                  </tr>
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
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
                  <h3 class="page-title">{{__('admin.all_purchase')}}</h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('purchase')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.back')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <form action="{{route('purchase-report')}}" method="GET">
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
                                            <label class="col-sm-3 col-form-label">{{__('admin.vendor')}}</label>
                                            <div class="col-sm-9">
                                                <select name="vendor_id" style="width: 100%">
                                                    <option value="all">All</option>
                                                    @foreach($vendor as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="col-sm-3 col-form-label">{{__('admin.inv_no')}}</label>
                                          <div class="col-sm-9">
                                              <input type="text" class="form-control" value="{{@$_GET['inv_id']}}" name="inv_id">
                                          </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="row">
                                    @if(Auth::user()->role == 'Super Admin')
                                    <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="col-sm-3 col-form-label">{{__('admin.status')}}</label>
                                          <div class="col-sm-9">
                                              <select name="status" style="width: 100%">
                                                  <option value="all" {{@$_GET['status'] == 'all' ? 'selected' : ''}}>All</option>
                                                  <option value="1" {{@$_GET['status'] == '1' ? 'selected' : ''}}>Active</option>
                                                  <option value="0" {{@$_GET['status'] == '0' ? 'selected' : ''}}>Deleted</option>
                                              </select>
                                          </div>
                                      </div>
                                    </div>
                                    @else
                                    <input type="hidden" name="status" value="1">
                                    @endif
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
                                            <th> {{__('admin.inv_no')}} </th>
                                            <th> {{__('admin.date')}} </th>
                                            <th> {{__('admin.vendor_name')}} </th>
                                            {{-- <th> {{__('admin.inv_type')}} </th> --}}
                                            <th> {{__('admin.product_name')}} </th>
                                            <th> {{__('admin.quantity')}} </th>
                                            <th> {{__('admin.price')}} </th>
                                            <th> {{__('admin.receive_amount')}} </th>
                                            <th> {{__('admin.due_amount')}} </th>
                                            <th> {{__('admin.total')}} </th>
                                            <th> {{__('admin.action')}} </th>
                                            <th class="note"> {{__('admin.note')}} </th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      @php 
                                      $page_qty_total = 0;
                                      $page_rcv_total = 0;
                                      $page_due_total = 0;
                                      $page_total = 0;
                                      $pq = 0;
                                      $price = 0;
                                      if(isset($_GET['page']) && $_GET['page']>0)
                                        $n = 1 + (($_GET['page'] - 1) * 10);
                                      else
                                        $n = 1;
                                      @endphp
                                      @if(count($datas) > 0)
                                        @foreach($datas as $row)
                                          <tr class="{{$row->status ? '' : 'text-light bg-danger'}}">
                                            <td><a href="{{route('purchase-invoice', $row->id)}}">{{$n++}}</a></td>
                                            <td><a href="{{route('purchase-invoice', $row->id)}}">{{$row->order_id}}</a></td>
                                            <td>{{date('d-m-Y', strtotime($row->date))}}</td>
                                            <td><a href="{{route('purchase-invoice', $row->id)}}">{{$row->vendor_name}}</a></td>
                                            {{-- <td>{{$row->order_type}}</td> --}}
                                            <td>
                                              @foreach(json_decode($row->products) as $p)
                                                  {{$p->product_name}}
                                                  <br>
                                                  {{@$p->product_details}}
                                                  @php
                                                  $pq += @$p->quantity ? $p->quantity : 0;
                                                  $price += @$p->price ? $p->price : 0;
                                                  $page_qty_total += @$p->quantity;
                                                  @endphp
                                              @endforeach
                                            </td>
                                            <td>{{$pq}}</td>
                                            <td>{{number_format($price,2)}}</td>
                                            <td>
                                              @foreach(json_decode($row->payment) as $p)
                                                @foreach($account as $ac)
                                                  @if($p->pid == $ac->id && $ac->type != 'Due')
                                                    {{$ac->name}}: {{number_format($p->receive_amount,2)}}<br>
                                                    @php
                                                    $page_rcv_total += $p->receive_amount;
                                                    @endphp
                                                  @endif
                                                @endforeach
                                              @endforeach
                                            </td>
                                            <td>
                                              {{number_format($row->total_due,2)}}
                                              @php
                                              $page_due_total += $row->total_due;
                                              @endphp
                                            </td>
                                            <td>
                                              {{number_format($row->total,2)}}
                                              @php
                                              $page_total += $row->total;
                                              $pq = 0;
                                              $price = 0;
                                              @endphp
                                            </td>
                                            <td>
                                              @if($row->status == '1')
                                                @if(hasModuleAccess('Purchase_Edit'))
                                                  <a href="{{route('purchase-trnx-edit', $row->id)}}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.edit')}}</a> 
                                                @endif
                                                @if(hasModuleAccess('Purchase_Delete'))
                                                  <a href="{{route('purchase-trnx-delete', $row->id)}}" class="btn btn-danger btn-rounded btn-sm" onclick="return confirm('Are you sure, you want to delete?')">{{__('admin.delete')}}</a>
                                                @endif
                                              @endif
                                            </td>
                                            <td class="note">{{$row->note}}</td>
                                          </tr>
                                        @endforeach
                                      @else
                                          <tr>
                                            <td colspan="12" class="text-center">{{__('admin.no_data_found')}}</td>
                                          </tr>
                                      @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right">Page Total: </td>
                                            <td>{{$page_qty_total}}</td>
                                            <td></td>
                                            <td>{{number_format($page_rcv_total, 2)}}</td>
                                            <td>{{number_format($page_due_total, 2)}}</td>
                                            <td>{{number_format($page_total, 2)}}</td>
                                            <td></td>
                                        </tr>
                                        @if(count($total)>0)
                                          <tr>
                                              <td colspan="7" class="text-right">Total: </td>
                                              <td>{{number_format($total[0]->total - $total[0]->total_due, 2)}}</td>
                                              <td>{{number_format($total[0]->total_due, 2)}}</td>
                                              <td>{{number_format($total[0]->total, 2)}}</td>
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
    <script>
      function openForm(){
        $('#addForm').removeClass('d-none');
      }
    </script>
  </body>
</html>
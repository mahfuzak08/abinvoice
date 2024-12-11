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
                <div class="row">
                  <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">{{ __('admin.sales_register') }}</h4>
                          <div _ngcontent-tmn-c13="" class="row container-fluid d-flex justify-content-between">
                            <div _ngcontent-tmn-c13="" class="col-lg-3 pl-0">
                              <p _ngcontent-tmn-c13="" class="mt-5 mb-2"><b _ngcontent-tmn-c13="">{{config('app.inv_name')}}</b></p>
                              <p _ngcontent-tmn-c13="">{{config('app.inv_address')}},<br _ngcontent-tmn-c13="">{{config('app.inv_mobile')}}.</p>

                              <p _ngcontent-tmn-c13="" class="mt-5 mb-2"><b _ngcontent-tmn-c13="">{{$invoice[0]->customer_name}}</b></p>
                              <p _ngcontent-tmn-c13="">{{$invoice[0]->mobile}},<br _ngcontent-tmn-c13="">{{$invoice[0]->address}}.</p>
                            </div>
                            <div _ngcontent-tmn-c13="" class="col-lg-3 pr-0">
                              <p _ngcontent-tmn-c13="" class="mt-5 mb-2 text-right">
                                <img src="{{ asset('pollifeed-logo.png') }}" style="position: relative; right: -90px;" alt="{{config('app.name')}} logo" />
                                <br>
                                <b _ngcontent-tmn-c13="">#INV-{{$invoice[0]->order_id}}</b></p>
                              <p _ngcontent-tmn-c13="" class="text-right">Date : {{date('d-m-Y', strtotime($invoice[0]->date))}}</p>
                            </div>
                          </div>
                          <div _ngcontent-tmn-c13="" class="container-fluid mt-5 d-flex justify-content-center w-100">
                            <div _ngcontent-tmn-c13="" class="table-responsive w-100">
                                <table _ngcontent-tmn-c13="" class="table">
                                  <thead _ngcontent-tmn-c13="">
                                    <tr _ngcontent-tmn-c13="" class="bg-dark text-white">
                                        <th _ngcontent-tmn-c13="">#</th>
                                        <th _ngcontent-tmn-c13="">Description</th>
                                        <th _ngcontent-tmn-c13="" class="text-right">Quantity</th>
                                        <th _ngcontent-tmn-c13="" class="text-right">Unit cost</th>
                                        <th _ngcontent-tmn-c13="" class="text-right">Total</th>
                                      </tr>
                                  </thead>
                                  <tbody _ngcontent-tmn-c13="">
                                    @php
                                      $products = json_decode($invoice[0]->products);
                                      $c=0;
                                      $total = 0;
                                    @endphp
                                    @foreach($products as $item)
                                    @php
                                      $total += $item->total;
                                    @endphp
                                    <tr _ngcontent-tmn-c13="" class="text-right">
                                      <td _ngcontent-tmn-c13="" class="text-left">{{++$c}}</td>
                                      <td _ngcontent-tmn-c13="" class="text-left">{{$item->product_name}}<br>{{@$item->product_details}}</td>
                                      <td _ngcontent-tmn-c13="">{{$item->quantity}}</td>
                                      <td _ngcontent-tmn-c13="">{{$item->price}}</td>
                                      <td _ngcontent-tmn-c13="">{{$item->total}}</td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                          </div>
                          <div _ngcontent-tmn-c13="" class="container-fluid mt-5 w-100">
                            {{-- <p _ngcontent-tmn-c13="" class="text-right mb-2">{{__('admin.subtotal')}}: {{$total}}</p> --}}
                            <p _ngcontent-tmn-c13="" class="text-right">{{__('admin.discount')}} : {{ $invoice[0]->discount }}</p>
                            <h4 _ngcontent-tmn-c13="" class="text-right mb-5">{{__('admin.total')}} : {{ $invoice[0]->total }}</h4>
                            <p _ngcontent-tmn-c13="" class="text-right">{{__('admin.receive_amount')}} : 
                              @foreach(json_decode($invoice[0]->payment) as $p)
                                @foreach($account as $ac)
                                  @if($p->pid == $ac->id)
                                    {{$ac->name}}: {{$p->receive_amount}}</p><p _ngcontent-tmn-c13="" class="text-right">
                                  @endif
                                @endforeach
                              @endforeach
                            </p>
                            <hr _ngcontent-tmn-c13="">
                          </div>
                          <div _ngcontent-tmn-c13="" class="container-fluid w-100">
                            <a _ngcontent-tmn-c13="" class="btn btn-primary float-right mt-4 ml-2" href="javascript:void(0)"><i _ngcontent-tmn-c13="" class="mdi mdi-printer mr-1"></i>Print</a>
                            {{-- <a _ngcontent-tmn-c13="" class="btn btn-success float-right mt-4" href="javascript:void(0)"><i _ngcontent-tmn-c13="" class="mdi mdi-send mr-1"></i>Send Invoice</a> --}}
                            <a _ngcontent-tmn-c13="" class="btn btn-secondary float-right mt-4" onclick="history.back()">Back</a>
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
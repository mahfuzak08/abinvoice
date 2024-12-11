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
                  <h3 class="page-title">{{ __('admin.purchase_register') }}</h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('purchase-report')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.all_purchase')}}</a></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                  <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        {{-- {{$order}} --}}
                        <form action="{{route('save-purchase')}}" method="POST" class="forms-sample table-responsive">
                          @csrf
                          <input type="hidden" name="order_id" value="{{@$order[0]->id}}">
                          <style>
                            .table td{
                              padding: 5px 2px;
                            }
                          </style>
                          <table class="table" style="min-width:500px">
                            <tr>
                              <td width="70%">
                                <input type="text" required name="vendor_new" list="vendor" id="vendor_id" value="{{@$order[0]->vendor_name}}" class="form-control" placeholder={{__('admin.vendor_name')}}">
                                <input type="hidden" name="vendor_id" value="{{@$order[0]->vendor_id}}" id="vendor_id_hidden">
                                <datalist id="vendor">
                                  @foreach($vendor as $v)
                                    <option value="{{$v->name}}" data-id="{{$v->id}}" data-mobile="{{$v->mobile}}" data-address="{{$v->address}}">
                                  @endforeach
                                </datalist>
                              </td>
                              <td width="30%">
                                <input type="date" value="{{@$order[0]->date}}" required name="date" class="form-control" placeholder="YYYY-MM-DD" value="{{date('d-m-Y')}}">
                              </td>
                            </tr>
                            <tr>
                              <td width="70%">
                                <input value="{{@$order[0]->address}}" type="text" name="address" id="address" class="form-control" placeholder="{{__('admin.address')}}">
                              </td>
                              <td width="30%">
                                <input type="text" value="{{@$order[0]->mobile}}" required name="mobile" id="mobile" class="form-control" placeholder="{{__('admin.mobile')}}">
                              </td>
                            </tr>
                          </table>
                          <table class="table">
                            <thead>
                              <tr>
                                <th width="2%"></th>
                                <th width="20%">{{__('admin.product_name')}}</th>
                                <th width="20%">{{__('admin.product_details')}}</th>
                                <th width="15%">{{__('admin.quantity')}}</th>
                                <th width="15%">{{__('admin.price')}}</th>
                                <th width="15%">{{__('admin.total')}}</th>
                                <th width="13%">{{__('admin.action')}}</th>
                              </tr>
                            </thead>
                            <tbody id="items">
                              @php
                                $products = json_decode($order[0]->products);
                                $c=0;
                                $total = 0;
                              @endphp
                              @foreach($products as $item)
                                @php
                                  $total += $item->total; 
                                @endphp
                                <tr class="item">
                                  <td class="sl">{{++$c}}</td>
                                  <td>
                                    <input type="text" name="product_name[]" value="{{$item->product_name}}" class="form-control" placeholder="{{__('admin.product_name')}}">
                                  </td>
                                  <td>
                                    <input type="text" name="product_details[]" value="{{@$item->product_details}}" class="form-control" placeholder="{{__('admin.product_details')}}">
                                  </td>
                                  <td>
                                    <input type="text" name="quantity[]" value="{{$item->quantity}}" class="qmp quantity form-control" placeholder="{{__('admin.quantity')}}">
                                  </td>
                                  <td>
                                    <input type="text" name="price[]" value="{{$item->price}}" class="qmp price form-control" placeholder="{{__('admin.price')}}">
                                  </td>
                                  <td>
                                    <input type="text" name="total[]" value="{{$item->total}}" disabled class="total form-control" placeholder="{{__('admin.total')}}">
                                  </td>
                                  <td>
                                    <input type="hidden" name="product_id[]" value="{{$item->pid}}">
                                    <button type="button" class="add_item_row btn btn-inverse-success btn-icon">
                                      <i class="mdi mdi-plus"></i>
                                    </button>
                                  </td>
                                </tr>
                              @endforeach
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="5" class="text-right">{{__('admin.subtotal')}}</td>
                                <td><input type="text" name="subtotal" value="{{$total}}" id="subtotal" class="form-control" placeholder="{{__('admin.subtotal')}}"></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td colspan="5" class="text-right">{{__('admin.discount')}}</td>
                                <td><input type="text" name="discount" value="{{@$order[0]->discount}}" id="discount" class="form-control" placeholder="{{__('admin.discount')}}"></td>
                                <td></td>
                              </tr>
                              {{-- <tr>
                                <td colspan="4" class="text-right">Tax</td>
                                <td><input type="text" name="tax" id="tax" class="form-control" placeholder="{{__('admin.tax')}}"></td>
                                <td></td>
                              </tr> --}}
                              <tr>
                                <td colspan="5" class="text-right">{{__('admin.total')}}</td>
                                <td><input type="text" value="{{@$order[0]->total}}" required name="total" id="total" class="form-control" placeholder="{{__('admin.total')}}"></td>
                                <td></td>
                              </tr>
                              @foreach(json_decode($order[0]->payment) as $p)
                              <tr class="payment_row">
                                <td colspan="4" class="text-right">{{__('admin.receive_amount')}}</td>
                                <td>
                                  <select name="payment_type[]" required id="payment_type" style="width: 100%;">
                                      @foreach($account as $ac)
                                        <option value="{{$ac->id}}" {{$p->pid == $ac->id ? 'selected' : ''}}>{{$ac->name}}</option>
                                      @endforeach
                                    </select>
                                  </td>
                                  <td>
                                    <input type="text" required name="receive_amount[]" value="{{$p->receive_amount}}" class="form-control" placeholder="{{__('admin.receive_amount')}}">
                                  </td>
                                  <td>
                                    <button type="button" class="add_payment_row btn btn-inverse-success btn-icon">
                                      <i class="mdi mdi-plus"></i>
                                    </button>
                                  </td>
                              </tr>
                              @endforeach
                              <tr>
                                <td colspan="5" class="text-right">
                                  <button type="submit" class="btn btn-rounded btn-primary btn-sm">{{ __('admin.save_now') }}</button>
                                </td>
                                <td>
                                  <a onclick="history.back()" class="btn btn-sm btn-rounded btn-secondary">{{ __('admin.cancel') }}</a>
                                </td>
                                <td></td>
                              </tr>
                            </tfoot>
                          </table>
                        </form>
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
      // vendor selection
      const input = document.getElementById('vendor_id');
      const datalist = document.getElementById('vendor');

      input.addEventListener('input', (event) => {
        const selectedLabel = event.target.value;
        const option = [...datalist.options].find((opt) => opt.value === selectedLabel);
        if (option) {
          $('#vendor_id_hidden').val(option.getAttribute('data-id'));
          $('#mobile').val(option.getAttribute('data-mobile'));
          $('#address').val(option.getAttribute('data-address'));
        }
      });

      // quantity and price multiplication
      $(document).on('change', "input.qmp", function() {
        let $tr = $(this).closest('.item');
        let q = Number($tr.find('.quantity').val());
        let p = Number($tr.find('.price').val());
        $tr.find('.total').val(q*p);
        
        let st = Number($('#subtotal').val());
        $('#subtotal').val(st+(q*p));
        st = Number($('#subtotal').val());
        $('#total').val(st);
      });
      
      // subtotal and total 
      $(document).on('change', "tfoot input", function() {
        var total = 0;
        $('.total').each(function(){
          const value = parseFloat($(this).val());
          if (!isNaN(value)) {
            total += value;
          }
        });
        
        $('#subtotal').val(total);
        let d = Number($('#discount').val());
        $('#total').val(total-d);
      });

      // add new item row
      $(document).on("click", ".add_item_row", function(){
        let rowlen = Number($("#items tr").length);
        let $tr = $(this).closest('.item');
        let $clone = $tr.clone();
        $clone.find('.sl').text(rowlen+1);
        $clone.find(':text').val('');
        $tr.after($clone);
        $(this).addClass('remove_row').removeClass('add_item_row');
        $(this).addClass('btn-inverse-danger').removeClass('btn-inverse-success');
        $(this).find('i').addClass('mdi-delete').removeClass('mdi-plus');
      });
      
      // remove item row
      $(document).on("click", ".remove_row", function(){
        let $tr = $(this).closest('.item');
        $tr.remove();
        let i = 1;
        // rearrange sl
        $("tr .sl").each(function() {
          $(this).text(i++)
        });
      });
      
      // add new payment row
      $(document).on("click", ".add_payment_row", function(){
        let $tr = $(this).closest('.payment_row');
        let $clone = $tr.clone();
        $clone.find(':text').val('');
        $tr.after($clone);
        $(this).addClass('remove_payment_row').removeClass('add_payment_row');
        $(this).addClass('btn-inverse-danger').removeClass('btn-inverse-success');
        $(this).find('i').addClass('mdi-delete').removeClass('mdi-plus');
      });
      
      // remove payment row
      $(document).on("click", ".remove_payment_row", function(){
        let $tr = $(this).closest('tr');
        $tr.remove();
      });
    </script>
  </body>
</html>
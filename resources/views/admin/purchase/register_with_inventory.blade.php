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
                        <table class="table" style="min-width: 500px">
                            <tr>
                              <td colspan="2" width="100%">
                                <div class="ui-widget">
                                  <input type="text" id="product-search" style="width: 100%" placeholder="{{__('Scan or Type Product Info')}}" autofocus=true>
                                </div>                            
                              </td>
                            </tr>
                        </table>
                        <form action="{{route('save-purchase')}}" method="POST" class="forms-sample table-responsive">
                          @csrf
                          <style>
                            .table td{
                              padding: 5px 2px;
                            }
                          </style>
                          <table class="table" style="min-width:500px">
                            <tr>
                              <td width="70%">
                                <input type="text" required name="vendor_new" list="vendor" id="vendor_id" class="form-control" placeholder="{{__('admin.vendor_name')}}">
                                <input type="hidden" name="vendor_id" id="vendor_id_hidden">
                                <datalist id="vendor">
                                  @foreach($vendor as $v)
                                    <option value="{{$v->name}}" data-id="{{$v->id}}" data-mobile="{{$v->mobile}}" data-address="{{$v->address}}">
                                  @endforeach
                                </datalist>
                              </td>
                              <td width="30%">
                                <input type="date" required name="date" class="form-control" placeholder="YYYY-MM-DD" value="{{date('d-m-Y')}}">
                              </td>
                            </tr>
                            <tr>
                              <td width="70%">
                                <input type="text" name="address" id="address" class="form-control" placeholder="{{__('admin.address')}}">
                              </td>
                              <td width="30%">
                                <input type="text" required name="mobile" id="mobile" class="form-control" placeholder="{{__('admin.mobile')}}">
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
                            </tbody>
                            <tfoot>
                              {{-- <tr>
                                <td colspan="5" class="text-right">{{__('admin.subtotal')}}</td>
                                <td><input type="text" name="subtotal" id="subtotal" class="form-control" placeholder="{{__('admin.subtotal')}}"></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td colspan="5" class="text-right">{{__('admin.discount')}}</td>
                                <td><input type="text" name="discount" id="discount" class="form-control" placeholder="{{__('admin.discount')}}"></td>
                                <td></td>
                              </tr> --}}
                              {{-- <tr>
                                <td colspan="4" class="text-right">Tax</td>
                                <td><input type="text" name="tax" id="tax" class="form-control" placeholder="{{__('admin.tax')}}"></td>
                                <td></td>
                              </tr> --}}
                              <tr>
                                <td colspan="5" class="text-right">{{__('admin.total')}}</td>
                                <td><input type="text" required name="total" id="total" class="form-control" placeholder="{{__('admin.total')}}"></td>
                                <td></td>
                              </tr>
                              <tr class="payment_row">
                                <td colspan="4" class="text-right">{{__('admin.receive_amount')}}</td>
                                <td>
                                  <select name="payment_type[]" required id="payment_type" style="width: 100%;">
                                  @foreach($account as $ac)
                                    <option value="{{$ac->id}}">{{$ac->name}}</option>
                                  @endforeach
                                  </select>
                                </td>
                                <td>
                                  <input type="text" required name="receive_amount[]" class="form-control" placeholder="{{__('admin.receive_amount')}}">
                                </td>
                                <td>
                                  <button type="button" class="add_payment_row btn btn-inverse-success btn-icon">
                                    <i class="mdi mdi-plus"></i>
                                  </button>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="5" class="text-right">
                                  @if(hasModuleAccess("Purchase_Add"))
                                  <button type="submit" class="btn btn-rounded btn-primary btn-sm">{{ __('admin.save_now') }}</button>
                                  @else
                                  <a class="btn btn-rounded btn-secondary btn-sm">{{ __('admin.save_now') }}</a>
                                  @endif
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
    <table class="hidden">
        <tr id="rowsample" class="item hidden purchase">
          <td class="sl">1</td>
          <td>
            <p class="product_name"></p>
            <input type="hidden" name="product_name[]" class="product_name">
          </td>
          <td>
            <p class="product_details"></p>
            <input type="hidden" name="product_details[]" class="product_details">
          </td>
          <td>
            <input type="text" name="quantity[]" onblur="adjust_price()" class="qmp quantity form-control" placeholder="{{__('admin.quantity')}}">
          </td>
          <td>
            <input type="text" name="price[]" onblur="adjust_price()" class="qmp price form-control" placeholder="{{__('admin.price')}}">
          </td>
          <td>
            <input type="text" name="total[]" disabled class="total form-control" placeholder="{{__('admin.total')}}">
          </td>
          <td>
            <input type="hidden" name="product_id[]" class="product_id">
            <button type="button" class="remove_row btn btn-inverse-danger btn-icon">
              <i class="mdi mdi-delete"></i>
            </button>
          </td>
        </tr>
    </table>
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
    </script>
    @include('admin._pos_script')
  </body>
</html>
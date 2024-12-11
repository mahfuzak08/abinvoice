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
                  <h3 class="page-title"> {{ __('admin.edit_product') }} </h3>
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
                                <form class="forms-sample" method="POST" action="{{ route('save-item') }}">
                                    <input type="hidden" name="product_id" value="{{$product->id}}">
                                    <input type="hidden" name="variant_id" value="{{$variant->id}}">
                                    <input type="hidden" name="product_tranx_id" value="{{$product_tranx[0]->id}}">
                                    <input type="hidden" name="old_vendor_id" value="{{$vendor_info[0]->id}}">
                                    <input type="hidden" name="purchase_id" value="{{$vendor_info[0]->purchase_id}}">
                                    <input type="hidden" name="old_order_id" value="{{$vendor_info[0]->order_id}}">
                                    @csrf
                                    <div class="form-group">
                                        <label>{{ __('admin.date') }}</label>
                                        <input type="date" class="form-control" name="date" value="{{$product_tranx[0]->date}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1" class="text-danger">{{ __('admin.product_name') }}</label>
                                        <input type="text" class="form-control" id="exampleInputName1" value="{{$product->name}}" name="name" placeholder="{{ __('admin.product_name') }}" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName2">{{ __('admin.details') }}</label>
                                        <input type="text" class="form-control" id="exampleInputName2" value="{{$product->description}}" name="description" placeholder="{{ __('admin.details') }}">
                                    </div>
                                    <div class="form-group">
                                      <label for="cat" class="text-danger">{{ __('admin.category') }}</label>
                                      <select id="cat" style="width: 100%;" name="category_id" required="true">
                                          <option value="">{{ __('Select Category') }}</option>
                                              @foreach($categories as $cat)
                                                  <option value="{{ $cat->id }}" {{$product->category_id == $cat->id ? 'selected': ''}}>{{$cat->name}}</option>
                                              @endforeach
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <label for="brand_name">{{ __('admin.brand') }}</label>
                                      <input list="brand" name="brand_name" id="brand_name" value="{{$product->brand_name}}" style="width: 100%">
                                      <datalist id="brand">
                                        @foreach($brands as $row)
                                          <option value="{{$row->brand_name}}">{{$row->brand_name}}</option>
                                        @endforeach
                                      </datalist>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="size-check">
                                          <input name="size_check" type="checkbox" id="size-check" value="1"> {{ __('admin.Allow_Product_Sizes_Color') }}
                                        </label>
                                    </div> --}}
                                    {{-- @if(!empty($variant->color) || $variant->color != null) --}}
                                    <div class="form-group" id="size-display">
                                      <div class="row">
                                        <div class="col-12 col-md-3">
                                          <label>
                                            {{ __('admin.Size_Name') }}
                                            <span>
																							{{ __('(eg. S,M,L,1Kg,5Kg etc)') }}
																						</span>
                                          </label>
                                          <input type="text" class="form-control sizename" name="sizes[]" value="{{$variant->size}}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>
                                            {{ __('admin.Color') }}
                                            {{-- <span>
																							{{ __('(eg. Red, Green, Blue etc)') }}
																						</span> --}}
                                          </label>
                                          <input type="text" class="form-control colorname" name="colors[]" value="{{$variant->color}}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>{{ __('admin.quantity') }}</label>
                                          <input type="text" class="form-control quantity" name="qtys[]" value="{{$product_tranx[0]->qty}}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>{{__('admin.purchase')}} {{ __('admin.price') }}</label>
                                          <input type="text" class="form-control price" name="buyprices[]" value="{{$variant->buy_price}}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>{{__('admin.sales')}} {{ __('admin.price') }}</label>
                                          <input type="text" class="form-control price" name="saleprices[]" value="{{$variant->sell_price}}">
                                        </div>
                                        <div class="col-12 col-md-1">
                                          {{-- <br />
                                          <button type="button" class="add_item_row btn btn-inverse-success btn-icon">
                                            <i class="mdi mdi-plus"></i>
                                          </button> --}}
                                        </div>
                                      </div>
                                    </div>
                                    {{-- @else
                                    <div class="form-group" id="stckprod">
                                      <div class="row">
                                        <div class="col-12 col-md-3">
                                          <label for="productmeasure">{{ __('admin.Product_Measurement') }}</label>
                                          <input list="product_measure" name="size" id="productmeasure">
                                          <datalist id="product_measure">
                                            <option value="Gram">{{ __('Gram') }}</option>
                                            <option value="Kilogram">{{ __('Kilogram') }}</option>
                                            <option value="Litre">{{ __('Litre') }}</option>
                                            <option value="Pound">{{ __('Pound') }}</option>
                                            <option value="Pcs">{{ __('Pcs') }}</option>
                                          </datalist>
                                        </div>
                                        <div class="col-12 col-md-3">
                                          <label>{{ __('admin.quantity') }}</label>
                                          <input type="text" class="form-control quantity" name="qty" placeholder="{{ __('admin.quantity') }}">
                                        </div>
                                        <div class="col-12 col-md-3">
                                          <label>{{__('admin.purchase')}} {{ __('admin.price') }}</label>
                                          <input type="text" class="form-control price" name="buyprice" placeholder="{{__('admin.purchase')}} {{ __('admin.price') }}">
                                        </div>
                                        <div class="col-12 col-md-3">
                                          <label>{{__('admin.sales')}} {{ __('admin.price') }}</label>
                                          <input type="text" class="form-control price" name="saleprice" placeholder="{{__('admin.sales')}} {{ __('admin.price') }}">
                                        </div>
                                      </div>
                                    </div>
                                    @endif --}}
                                    <div class="form-group">
                                        <label for="batchno">{{ __('admin.batchno') }}</label>
                                        <input type="text" class="form-control" id="batchno" value="{{$product_tranx[0]->batch_no}}" name="batchno">
                                    </div>
                                    <div class="form-group">
                                        <input name="has_sl" type="hidden" value="no">
                                        <label for="size-check">
                                          <input name="has_sl" type="checkbox" value="yes" {{$product->has_ls == 'yes' ? 'checked': ''}}> {{ __('admin.Product_has_Serial_No') }}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="expirydate">{{ __('admin.expirydate') }}</label>
                                        <input type="date" class="form-control" id="expirydate" value="{{$product_tranx[0]->expiry_date}}" name="expirydate">
                                    </div>
                                    <div class="form-group">
                                      <label for="tag_name">{{ __('admin.tag') }}</label>
                                      <input list="tags" name="tags" id="tag_name" value="{{$product->tags}}" style="width: 100%">
                                      <datalist id="tags">
                                        @foreach($tags as $row)
                                          <option value="{{$row->tag_name}}">{{$row->tag_name}}</option>
                                        @endforeach
                                      </datalist>
                                    </div>
                                    <div class="form-group">
                                      <label for="vendor_name">{{ __('admin.vendor') }}</label>
                                      <input type="hidden" name="vendor_id" id="vendor_id">
                                      <input list="vendors" name="vendor_name" id="vendor_name" value="{{$vendor_info[0]->name}}" style="width: 100%">
                                      <datalist id="vendors">
                                        @foreach($vendors as $row)
                                          <option data-id="{{$row->id}}" value="{{$row->name}}">{{$row->name}}</option>
                                        @endforeach
                                      </datalist>
                                    </div>
                                    <div class="form-group">
                                        <label for="barcode">{{ __('admin.barcode') }}</label>
                                        <input type="text" class="form-control" id="barcode" name="barcode" value="{{$product->barcode}}">
                                    </div>
                                    <button type="submit" class="btn btn-rounded btn-primary btn-sm me-2">{{ __('admin.update') }}</button><br><br>
                                    <a onclick="history.back()" class="btn btn-sm btn-rounded btn-secondary">{{ __('admin.cancel') }}</a>
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
      $("#size-check").change(function() {
          if(this.checked) {
              $("#size-display").show();
              $("#stckprod").hide();
          }
          else
          {
              $("#size-display").hide();
              $("#stckprod").show();

          }
      });

      // add new item row
      $(document).on("click", ".add_item_row", function(){
        let $row = $(this).closest('.row');
        let $clone = $row.clone();
        $clone.find(':text').val('');
        $row.after($clone);
        $(this).addClass('remove_row').removeClass('add_item_row');
        $(this).addClass('btn-inverse-danger').removeClass('btn-inverse-success');
        $(this).find('i').addClass('mdi-delete').removeClass('mdi-plus');
      });
      // remove item row
      $(document).on("click", ".remove_row", function(){
        let $row = $(this).closest('.row');
        $row.remove();
      });

      // customer selection
      const input = document.getElementById('vendor_name');
      const datalist = document.getElementById('vendors');

      input.addEventListener('input', (event) => {
        let selectedLabel = event.target.value;
        let option = [...datalist.options].find((opt) => opt.value === selectedLabel);
        if (option) {
          $('#vendor_id').val(option.getAttribute('data-id'));
        }else{
          $('#vendor_id').val(0);
        }
      });
    </script>
  </body>
</html>
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
                  <h3 class="page-title"> {{ __('admin.add_product') }} </h3>
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
                                    @csrf
                                    <div class="form-group">
                                        <label>{{ __('admin.date') }}</label>
                                        <input type="date" class="form-control" name="date" value="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName1" class="text-danger">{{ __('admin.product_name') }}</label>
                                        <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="{{ __('admin.product_name') }}" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputName2">{{ __('admin.details') }}</label>
                                        <input type="text" class="form-control" id="exampleInputName2" name="description" placeholder="{{ __('admin.details') }}">
                                    </div>
                                    <div class="form-group">
                                      <label for="cat" class="text-danger">{{ __('admin.category') }}</label>
                                      <select id="cat" style="width: 100%;" name="category_id" required="true">
                                          <option value="">{{ __('Select Category') }}</option>
                                              @foreach($categories as $cat)
                                                  <option value="{{ $cat->id }}">{{$cat->name}}</option>
                                              @endforeach
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <label for="brand_name">{{ __('admin.brand') }}</label>
                                      <input list="brand" name="brand_name" id="brand_name" style="width: 100%">
                                      <datalist id="brand">
                                        @foreach($brands as $row)
                                          <option value="{{$row->brand_name}}">{{$row->brand_name}}</option>
                                        @endforeach
                                      </datalist>
                                    </div>
                                    <div class="form-group">
                                        <label for="size-check">
                                          <input name="size_check" type="checkbox" id="size-check" value="1"> {{ __('admin.Allow_Product_Sizes_Color') }}
                                        </label>
                                    </div>
                                    <div class="form-group hidden" id="size-display">
                                      <div class="row">
                                        <div class="col-12 col-md-3">
                                          <label>
                                            {{ __('admin.Size_Name') }}
                                            <span>
																							{{ __('(eg. S,M,L,1Kg,5Kg etc)') }}
																						</span>
                                          </label>
                                          <input type="text" class="form-control sizename" name="sizes[]" placeholder="{{ __('admin.Size_Name') }}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>
                                            {{ __('admin.Color') }}
                                            {{-- <span>
																							{{ __('(eg. Red, Green, Blue etc)') }}
																						</span> --}}
                                          </label>
                                          <input type="text" class="form-control colorname" name="colors[]" placeholder="{{ __('admin.Color') }}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>{{ __('admin.quantity') }}</label>
                                          <input type="text" class="form-control quantity" name="qtys[]" placeholder="Qty">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>{{__('admin.purchase')}} {{ __('admin.price') }}</label>
                                          <input type="text" class="form-control price" name="buyprices[]" placeholder="{{__('admin.purchase')}} {{ __('admin.price') }}">
                                        </div>
                                        <div class="col-6 col-md-2">
                                          <label>{{__('admin.sales')}} {{ __('admin.price') }}</label>
                                          <input type="text" class="form-control price" name="saleprices[]" placeholder="{{__('admin.sales')}} {{ __('admin.price') }}">
                                        </div>
                                        <div class="col-12 col-md-1">
                                          <br />
                                          <button type="button" class="add_item_row btn btn-inverse-success btn-icon">
                                            <i class="mdi mdi-plus"></i>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
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
                                    <div class="form-group">
                                        <label for="batchno">{{ __('admin.batchno') }}</label>
                                        <input type="text" class="form-control" id="batchno" name="batchno" placeholder="{{ __('admin.batchno') }}">
                                    </div>
                                    <div class="form-group">
                                        <input name="has_sl" type="hidden" value="no">
                                        <label for="size-check">
                                          <input name="has_sl" type="checkbox" value="yes"> {{ __('admin.Product_has_Serial_No') }}
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="expirydate">{{ __('admin.expirydate') }}</label>
                                        <input type="date" class="form-control" id="expirydate" name="expirydate" placeholder="{{ __('admin.expirydate') }}">
                                    </div>
                                    <div class="form-group">
                                      <label for="tag_name">{{ __('admin.tag') }}</label>
                                      <input list="tags" name="tags" id="tag_name" style="width: 100%">
                                      <datalist id="tags">
                                        @foreach($tags as $row)
                                          <option value="{{$row->tag_name}}">{{$row->tag_name}}</option>
                                        @endforeach
                                      </datalist>
                                    </div>
                                    <div class="form-group">
                                      <label for="vendor_name">{{ __('admin.vendor') }}</label>
                                      <input type="hidden" name="vendor_id" id="vendor_id">
                                      <input list="vendors" name="vendor_name" id="vendor_name" style="width: 100%">
                                      <datalist id="vendors">
                                        @foreach($vendors as $row)
                                          <option data-id="{{$row->id}}" value="{{$row->name}}">{{$row->name}}</option>
                                        @endforeach
                                      </datalist>
                                    </div>
                                    <div class="form-group">
                                        <label for="barcode">{{ __('admin.barcode') }}</label>
                                        <input type="text" class="form-control" id="barcode" name="barcode" placeholder="{{ __('admin.barcode') }}">
                                    </div>
                                    <button type="submit" class="btn btn-rounded btn-primary btn-sm me-2">{{ __('admin.save_now') }}</button><br><br>
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
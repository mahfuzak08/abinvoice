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
                  <h3 class="page-title"> {{__('admin.products')}} </h3>
                  @if(hasModuleAccess('Accounts_Add'))
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="{{route('add-item')}}" class="btn btn-rounded btn-sm btn-success">{{__('admin.add_new')}}</a></li>
                    </ol>
                  </nav>
                  @endif
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                      <div class="card">
                        <div class="card-body">
                          <form action="{{route('products')}}" method="GET">
                            @csrf
                            @php 
                            $sv = isset($_GET['search']) ? $_GET['search'] : '';
                            @endphp
                            <div class="row">
                              <input type="text" name="search" class="col-12 col-md-10" value="{{$sv}}" placeholder="{{__('admin.what_you_want_to_find')}}">
                              <button type="submit" class="col-12 col-md-2 btn btn-info">{{__('admin.find')}}</button>
                            </div>
                          </form>
                          <div class="row table-responsive">
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th> {{__('admin.sl')}} </th>
                                  <th> {{__('admin.name')}} </th>
                                  <th> {{__('admin.brand')}} </th>
                                  <th> {{__('admin.category')}} </th>
                                  <th> {{__('admin.Size_Name')}} </th>
                                  <th> {{__('admin.quantity')}} </th>
                                  <th> {{__('admin.purchase')}} {{ __('admin.price') }} </th>
                                  <th> {{__('admin.sales')}} {{ __('admin.price') }} </th>
                                  <th> {{__('admin.action')}} </th>
                                </tr>
                              </thead>
                              <tbody>
                                @if(count($products) > 0)
                                  @php
                                  $i = 1;
                                  @endphp
                                  @foreach ($products as $row)
                                    <tr>
                                      <td>{{$i++}}</td>
                                      <td> {{$row->name}} </td>
                                      <td> {{$row->brand_name}} </td>
                                      <td> {{$row->category_name}} </td>
                                      <td> {{$row->size}} </td>
                                      <td> {{e2bn($row->qty)}} </td>
                                      <td> {{e2bn($row->buy_price)}} </td>
                                      <td> {{e2bn($row->price)}} </td>
                                      <td>
                                        {{-- <a href="#" class="btn btn-info btn-rounded btn-sm">{{__('admin.barcode')}}</a> --}}
                                        {{-- <a href="#" class="btn btn-success btn-rounded btn-sm">{{__('admin.reorder')}}</a> --}}
                                        <a href="{{ URL::route('add-item', ['variant_id' => $row->variant_id, 'action' => 'edit']) }}" class="btn btn-warning btn-rounded btn-sm">{{__('admin.edit')}}</a>
                                        <a href="{{ URL::route('add-item', ['variant_id' => $row->variant_id, 'action' => 'delete']) }}" class="btn btn-danger btn-rounded btn-sm">{{__('admin.delete')}}</a>
                                      </td>
                                    </tr>
                                  @endforeach
                                @else
                                  <tr>
                                    <td colspan="9" class="text-center">{{__('admin.no_data_found')}}</td>
                                  </tr>
                                @endif
                                
                              </tbody>
                            </table>
                          </div>
                        </div>
                        {{ $products->onEachSide(3)->links() }}
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
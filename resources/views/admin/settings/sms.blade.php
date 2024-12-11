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
                  <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                      <i class="mdi mdi-home"></i>
                    </span> {{ __('admin.sms_manage')}} 
                  </h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><b>Your Balance is BDT {{$bal}}</b></li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <form action="{{route('sms')}}" method="GET">
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
                                            <th> {{__('admin.date')}} </th>
                                            <th> {{__('admin.mobile')}} </th>
                                            <th> {{__('admin.details')}} </th>
                                            <th> {{__('admin.status')}} </th>
                                            <th> {{__('admin.action')}} </th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      @if(count($sms) > 0)
                                        @php 
                                        if(isset($_GET['page']) && $_GET['page']>0)
                                          $n = 1 + (($_GET['page'] - 1) * 10);
                                        else
                                          $n = 1;
                                        @endphp
                                        @foreach($sms as $row)
                                          @php 
                                          $resendbtn = true;
                                          @endphp
                                          <tr>
                                            <td>{{$n++}}</td>
                                            <td>{{date('d-m-Y H:i:s', strtotime($row->created_at))}}</td>
                                            <td>{{$row->contacts}}</td>
                                            <td>{{$row->msg}}</td>
                                            <td>
                                              @php
                                              if(strpos($row->response, 'SMS SUBMITTED') !== false){
                                                echo 'Send';
                                                $resendbtn = false;
                                              }
                                              elseif($row->response == 1007)
                                                echo 'Balance Insufficient';
                                              else 
                                                echo $row->response;
                                              @endphp
                                            </td>
                                            <td>
                                              @if($resendbtn)
                                                <a href="{{ URL::route('sms', ['id' => $row->id]) }}">Resend</a>
                                              @endif
                                            </td>
                                          </tr>
                                        @endforeach
                                      @else
                                          <tr>
                                            <td colspan="5" class="text-center">{{__('admin.no_data_found')}}</td>
                                          </tr>
                                      @endif
                                    </tbody>
                                </table>
                              </div>
                              {{ $sms->onEachSide(3)->links() }}
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
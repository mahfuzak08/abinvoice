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
            <div class="content-wrapper" style="max-height: 100px">
              <form id="dataForm" action="{{route('emp-report')}}" method="GET">
                @csrf
                <div class="page-header row">
                    <div class="col-4">
                      <h3 class="page-title">{{__('admin.employee')}}</h3>
                    </div>
                    <div class="col-3">
                      <select name="empid" style="width: 100%">
                        <option value="all">All</option>
                        @for($i=0; $i<count($employee); $i++)
                          <option value="{{$employee[$i]['id']}}">{{$employee[$i]['name']}}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="col-2"><input type="month" name="month" class="form-control"></div>
                    <div class="col-3"><button class="btn btn-info" type="submit">{{__('admin.old_attendance')}}</button></div>
                  </div>
                </form>
                  {{-- <nav> --}}
                    {{-- <ol> --}}
                        {{-- <li> --}}
                          {{-- <form id="dataForm" action="{{route('emp-report')}}" method="GET">
                            @csrf
                            <input type="month" name="month" class="form-control">
                            <button class="btn btn-info" type="submit">{{__('admin.old_attendance')}}</button>
                          </form> --}}
                          {{-- <button id="printButton" class="btn btn-success hidden">Print</button> --}}
                        {{-- </li> --}}
                    {{-- </ol> --}}
                  {{-- </nav> --}}
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <div class="row table-responsive printable-content">
                                <div class="text-center">
                                  <h2>{{config('app.name')}}</h2>
                                  <h3>Employee Attendance</h3>
                                  <h3>for</h3>
                                  <h3>{{$monthYear}}</h3>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> {{__('admin.name')}} </th>
                                            @for($i=1; $i<=$totalDays; $i++)
                                                <th>{{$i}}</th>
                                            @endfor
                                            <th>Total</th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      @php
                                          $sl=1;
                                      @endphp
                                      @for($n=0; $n<count($employee); $n++)
                                        <tr>
                                            <td>{{$sl++}}</td>
                                            <td>{{$employee[$n]['name']}}</td>
                                            @php
                                            $total = 0;
                                            @endphp
                                            @for($i=1; $i<=$totalDays; $i++)
                                                @php 
                                                $flag = false;
                                                @endphp
                                                @for($j=0; $j<count($employee[$n]['attendance']); $j++)
                                                    @if(($employee[$n]['attendance'][$j]['date'] == $inputYearMonth."-".str_pad($i, 2, '0', STR_PAD_LEFT)) && $employee[$n]['attendance'][$j]['hours'] > 0)
                                                        @php 
                                                        $flag = true;
                                                        break;
                                                        @endphp
                                                    @endif
                                                @endfor
                                                @if($flag)
                                                    @php
                                                    $total++;
                                                    @endphp
                                                    <td>Y</td>
                                                @else
                                                    <td> </td>
                                                @endif
                                            @endfor
                                            <td>
                                              {{$total}}
                                            </td>
                                        </tr>
                                      @endfor
                                    </tbody>
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
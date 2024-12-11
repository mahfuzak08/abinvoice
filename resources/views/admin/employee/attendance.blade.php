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
                  <h3 class="page-title">{{__('admin.employee')}}</h3>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                          <form id="dataForm" action="{{route('attendance')}}" method="GET">
                            @csrf
                            <input type="date" id="oldDate" name="oldDate"><br>
                            <button class="btn btn-info" type="submit">{{__('admin.old_attendance')}}</button>
                          </form>
                        </li>
                    </ol>
                  </nav>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                              <form action="{{route('attendance')}}" method="GET">
                                @csrf
                                @php 
                                $sv = isset($_GET['search']) ? $_GET['search'] : '';
                                @endphp
                                <div class="row">
                                  <input type="text" name="search" class="col-12 col-md-10" value="{{$sv}}" placeholder="{{__('admin.what_you_want_to_find')}}">
                                  <button type="submit" class="col-12 col-md-2 btn btn-info">{{__('admin.find')}}</button>
                                </div>
                              </form>
                              <br>
                              <div class="row table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                              <input _ngcontent-gsm-c19="" checked="true" id="masterCheckbox" class="form-check-input" type="checkbox">
                                            </th>
                                            <th> {{__('admin.name')}} </th>
                                            <th> {{__('admin.mobile')}} </th>
                                            <th> {{__('admin.designation')}} </th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                      <form class="forms-sample" method="POST" action="{{ route('save-attendance') }}">
                                        @csrf
                                        <input type="date" name="attendance-date" value="{{isset($_GET['oldDate'])? $_GET['oldDate'] : date('d-m-Y');}}">
                                        @if(count($employee) > 0)
                                          @php 
                                            $sl = 0;
                                          @endphp
                                          @foreach($employee as $row)
                                            <tr>
                                              <td>
                                                <input name="empid[{{$sl}}]" value="{{$row['id']}}" type="hidden">
                                                <input name="attendance[{{$sl}}]" value="false" type="hidden">
                                                <input name="attendance[{{$sl++}}]" {{$row['attendance'] == 'yes' ? 'checked': '';}} value="true" class="form-check-input checkbox" type="checkbox">
                                              </td>
                                              <td>{{$row['name']}}</td>
                                              <td>{{$row['mobile']}}</td>
                                              <td>{{$row['designation']}}</td>
                                            </tr>
                                          @endforeach
                                          @if(hasModuleAccess('Employee_Attendance'))
                                          <tr>
                                            <td colspan="4"><button type="submit" class="btn btn-successbtn btn-rounded btn-primary btn-sm me-2" name="save">{{isset($_GET['oldDate']) ? __('admin.update') : __('admin.save_now')}}</button>
                                          </tr>
                                          @endif
                                        @else
                                            <tr>
                                              <td colspan="4" class="text-center">{{__('admin.no_data_found')}}</td>
                                            </tr>
                                        @endif
                                      </form>
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
    <script>
        var masterCheckbox = document.getElementById('masterCheckbox');
        var checkboxes = document.querySelectorAll('.checkbox');
        function toggleCheckboxes() {
          checkboxes.forEach(function(checkbox) {
            checkbox.checked = masterCheckbox.checked;
          });
        }

        masterCheckbox.addEventListener('change', toggleCheckboxes);
    </script>
  </body>
</html>
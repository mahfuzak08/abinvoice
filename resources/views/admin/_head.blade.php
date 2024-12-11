<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>{{config('app.name')}}</title>
<!-- PWA  -->
<meta name="theme-color" content="#6777ef"/>
<link rel="apple-touch-icon" href="{{ asset('admin/assets/images/pollifeed-logo.gif') }}">
<link rel="manifest" href="{{ asset('/manifest.json') }}">
<!-- plugins:css -->
{{-- <link rel="stylesheet" href="admin/assets/vendors/mdi/css/materialdesignicons.min.css"> --}}
{{-- <link rel="stylesheet" href="admin/assets/vendors/css/vendor.bundle.base.css"> --}}
<link rel="stylesheet" href="{{asset('admin/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/assets/vendors/css/vendor.bundle.base.css')}}">
<!-- endinject -->
<!-- Plugin css for this page -->
<!-- End plugin css for this page -->
<!-- inject:css -->
<!-- endinject -->
<!-- Layout styles -->
{{-- <link rel="stylesheet" href="admin/assets/css/style.css"> --}}
<link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">
<!-- End layout styles -->
{{-- <link rel="shortcut icon" href="admin/assets/images/favicon.ico" /> --}}
<link rel="shortcut icon" href="{{asset('admin/assets/images/favicon.ico')}}" />
<link rel="stylesheet" href="{{asset('admin/assets/css/jquery-ui.css')}}">
<style>
    .form-group-margin-bottom-off{
        margin-bottom: unset;
    }
    .form-group-margin-bottom-off .form-group label{
        margin-bottom: unset;
    }
    .form-group-margin-bottom-off .form-group label{
        margin-bottom: unset;
    }
    .form-control-border-off{
        border: none;
    }
    .nav-profile-text{
        overflow: hidden;
    }
    .nav-profile-text .name-row{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .collapse.show{
        visibility: visible !important;
    }
    .note{
        max-width:200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* print.css */
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-content {
            visibility: visible;
        }
    }

</style>
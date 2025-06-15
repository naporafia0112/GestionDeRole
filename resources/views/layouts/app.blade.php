<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>StagePro/DIPRH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Plugins CSS -->
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />

    <!-- App CSS -->
    <link href="{{ asset('assets/css/config/default/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="loading"
      data-layout-mode="default"
      data-layout-color="light"
      data-layout-width="fluid"
      data-topbar-color="dark"
      data-menu-position="fixed"
      data-leftbar-color="light"
      data-leftbar-size='default'
      data-sidebar-user='false'>

    <!-- 🧭 NAVBAR -->
    <div id="wrapper">
        @include('partials.navbar')
        @include('partials.sidebar')


        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>

    <!-- Vendor JS -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- Plugins JS -->
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/libs/selectize/js/standalone/selectize.min.js') }}"></script>

    <!-- Dashboard Init JS -->
    <script src="{{ asset('assets/js/pages/dashboard-1.init.js') }}"></script>

    <!-- App JS -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
</body>
</html>

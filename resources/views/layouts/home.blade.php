<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>StagePro/DIPRH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link href="https://cdn.materialdesignicons.com/7.2.96/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.jpg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Plugins CSS -->
    <link href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- App CSS -->
    <link href="{{ asset('assets/css/config/default/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    @stack('styles')
    @livewireStyles
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
        @include('layouts.barres.navbar')
        @include('layouts.barres.sidebar')


        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-all me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-block-helper me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif
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
    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('fade');
            });
            }, 5000);
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </script>
<!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Affichage d'une alerte en cas de succès -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: @json(session('success')),
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: @json(session('error')),
                    timer: 4000,
                    showConfirmButton: true
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    html: `<ul style="text-align:left;">{!! implode('', $errors->all('<li>:message</li>')) !!}</ul>`,
                    showConfirmButton: true
                });
            @endif
        });
    </script>
    @endpush
    @vite('resources/js/app.js')
    @stack('scripts')
    @livewireScripts
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

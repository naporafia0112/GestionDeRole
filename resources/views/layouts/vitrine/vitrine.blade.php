<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', 'DIPRH vitrine')</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets-vitrine/img/logo.jpg') }}" rel="icon">
  <link href="{{ asset('assets-vitrine/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets-vitrine/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assets-vitrine/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{asset('assets-vitrine/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('assets-vitrine/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('assets-vitrine/css/main.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: eStartup
  * Template URL: https://bootstrapmade.com/estartup-bootstrap-landing-page-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">
    @include('layouts.vitrine.navbar')
    <main  style="margin-bottom: 25px;">
        @yield('content')
    </main>
    @include('layouts.vitrine.footer')
<!-- Vendor JS Files -->
  <script src="{{asset('assets-vitrine/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets-vitrine/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets-vitrine/vendor/aos/aos.js')}}"></script>
  <script src="{{ asset('assets-vitrine/vendor/glightbox/js/glightbox.min.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{asset('assets-vitrine/js/main.js')}}"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    once: true,          // une seule fois
    duration: 800,       // durée de l'animation
    easing: 'ease-in-out' // type d'animation
  });
</script>
<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Affichage d'une alerte en cas de succès -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: '{{ session("success") }}',
        confirmButtonText: 'OK',
        timer: 6000
    });
</script>
@endif

</body>
</html>

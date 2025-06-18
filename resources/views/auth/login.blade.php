<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from coderthemes.com/ubold/layouts/default/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 May 2022 01:15:45 GMT -->
<head>
        <meta charset="utf-8" />
        <title>DIPRH</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.icon') }}">

		<!-- App css -->
		<link href="{{asset ('assets/css/config/default/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style"/>
		<!-- icons -->
		<link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

    </head>

<body class="loading authentication-bg" style="--ct-auth-bg: #e3f2fd; --ct-auth-bg-alt: #66b2fe;">
        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">

                                <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        <a href="index.html" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <img src="../assets/images/logo.jpg" alt="" height="80">
                                            </span>
                                        </a>

                                        <a href="index.html" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <img src="../assets/images/logo.jpg" alt="" height="80">
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Entrer votre mail et votre mot de passe pour vous connecter á la plateforme</p>
                                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label class="form-label" for="email" :value="__('Adresse mail')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" placeholder="Enter your email" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group input-group-merge">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <div class="input-group-text" data-password="false" style="cursor: pointer;">
                        <span class="password-eye"></span>
                    </div>
                </div>

                @error('password')
                    <div class="invalid-feedback mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>


                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Se rappeller de moi') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-blue-600 hover:text-blue-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Mot de passe oublié?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
        </div>
        </div>

        <!-- end page -->


        <footer class="footer footer-alt">
            Allez sur le site <a href="https://www.cagecfi.com/" class="text-white-50">CAGECFI</a>
        </footer>

        <!-- Vendor js -->
        <script src="{{asset ( 'assets/js/vendor.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('assets/js/app.min.js') }}"></script>

    </body>

<!-- Mirrored from coderthemes.com/ubold/layouts/default/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 30 May 2022 01:15:45 GMT -->
</html>

@extends('layouts.app')

@section('content')
<body class="loading authentication-bg authentication-bg-pattern">

<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card bg-pattern">

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <div class="auth-logo">
                                <a href="#" class="logo logo-dark text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets/images/logo.jpg') }}" alt="" height="100">
                                    </span>
                                </a>

                                <a href="{{ route('user.index') }}" class="logo logo-light text-center">
                                    <span class="logo-lg">
                                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="22">
                                    </span>
                                </a>
                            </div>
                            <p class="text-muted mb-4 mt-3">Ajouter un nouvel utilisateur au système</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input class="form-control" type="text" id="name" name="name" placeholder="Entrez le nom complet" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Entrez l'adresse email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Créez un mot de passe" required>
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmation mot de passe</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirmez le mot de passe" required>
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="role_id" class="form-label">Rôle</label>
                                <select id="role_id" name="role_id" class="form-control" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="text-center d-grid">
                                <button class="btn btn-primary" type="submit">Créer l'utilisateur</button>
                            </div>

                        </form>

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-white-50">Retour à la <a href="{{ route('user.index') }}" class="text-white ms-1"><b>liste des utilisateurs</b></a></p>
                            </div>
                        </div>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

@endsection

@push('scripts')
<script>
    // Script pour basculer la visibilité du mot de passe
    document.querySelectorAll('[data-password]').forEach(element => {
        element.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.setAttribute('data-password', !isPassword);
        });
    });
</script>
@endpush

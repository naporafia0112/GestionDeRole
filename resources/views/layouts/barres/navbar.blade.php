<div class="navbar-custom">
    <div class="container-fluid d-flex justify-content-between align-items-center px-4">

        {{-- Logo + Nom de l'application alignés à gauche --}}
        <div class="d-flex align-items-center">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" height="35" class="me-2">
                <span class="fw-bold fs-5 text-white d-none d-md-inline" >{{ config('app.name', 'DIPRH') }}</span>
            </a>
        </div>

        {{-- Menu à droite --}}
        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

            {{-- Notifications --}}
            <li class="dropdown notification-list topbar-dropdown me-3">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button">
                    <i class="fe-bell noti-icon"></i>
                    <span class="badge bg-danger rounded-circle noti-icon-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">Notifications</h5>
                    </div>
                    <div class="noti-scroll" data-simplebar>
                        <a href="#" class="dropdown-item notify-item">
                            <div class="notify-icon bg-info"><i class="mdi mdi-comment-account-outline"></i></div>
                            <p class="notify-details">Nouvelle candidature</p>
                            <p class="text-muted mb-0"><small>Il y a 2 minutes</small></p>
                        </a>
                        <!-- Autres notifications -->
                    </div>
                    <a href="#" class="dropdown-item text-center text-primary notify-item notify-all">
                        Voir toutes <i class="fe-arrow-right"></i>
                    </a>
                </div>
            </li>

            {{-- Utilisateur connecté (menu déroulant) --}}
            <li class="dropdown topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button">
                    <img src="https://i.pinimg.com/736x/15/02/d0/1502d08ab9ee14a185e3ee5c26c621e9.jpg" alt="user-image" class="rounded-circle" height="32">
                    <span class="pro-user-name ms-1">
                        {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown">

                    <!-- Mon Profil -->
                    <a href="{{ route('profile.edit') }}" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>Mon profil</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- Déconnexion -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item notify-item">
                            <i class="fe-log-out me-1"></i>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            </li>

            {{-- Bouton paramètres (facultatif) --}}
            <li class="notification-list ms-2">
                <a href="javascript:void(0);" class="nav-link waves-effect waves-light">
                    <i class="fe-settings noti-icon"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

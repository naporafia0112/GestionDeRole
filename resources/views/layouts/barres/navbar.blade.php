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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fe-bell noti-icon"></i>
                    @if(isset($notifications) && count($notifications) > 0)
                        <span class="badge bg-danger rounded-circle noti-icon-badge">{{ count($notifications) }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    @forelse ($notifications as $notif)
                        <a href="{{ $notif['link'] }}" class="dropdown-item">
                            <i class="{{ $notif['icon'] }}"></i> {{ $notif['message'] }}
                        </a>
                    @empty
                        <span class="dropdown-item text-muted">Aucune notification</span>
                    @endforelse
                </div>
            </li>

            {{-- Utilisateur connecté (menu déroulant) --}}
            <li class="">
                <a class="nav-link nav-user me-0 waves-effect waves-light" href="{{ route('profil.show') }}" role="button">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=100&background=4e54c8&color=fff" class="rounded-circle" alt="Avatar">
                    <span class="pro-user-name ms-1">
                        {{ Auth::user()->name }}
                    </span>
                </a>
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

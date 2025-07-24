<div class="navbar-custom">
    <div class="container-fluid d-flex justify-content-between align-items-center px-4">

        {{-- Logo + Nom --}}
        <div class="d-flex align-items-center">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" height="35" class="me-2">
                <span class="fw-bold fs-5 text-white d-none d-md-inline">{{ config('app.name', 'DIPRH') }}</span>
            </a>
        </div>

        {{-- Menu à droite --}}
        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

            {{-- Notifications --}}
            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fe-bell noti-icon"></i>
                    @if(isset($notifications) && count($notifications) > 0)
                        <span class="badge bg-danger rounded-circle noti-icon-badge">{{ count($notifications) }}</span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">
                            <span class="float-end">
                                <a href="#" class="text-dark"><small>Tout effacer</small></a>
                            </span>
                            Notifications
                        </h5>
                    </div>

                    <div class="noti-scroll" data-simplebar style="max-height: 300px;">
                        @forelse ($notifications as $notif)
                            <a href="{{ $notif['link'] }}" class="dropdown-item notify-item">
                                <div class="notify-icon {{ $notif['bg'] ?? 'bg-primary' }}">
                                    @if(isset($notif['image']))
                                        <img src="{{ asset($notif['image']) }}" class="img-fluid rounded-circle" alt="" />
                                    @else
                                        <i class="{{ $notif['icon'] }}"></i>
                                    @endif
                                </div>
                                <p class="notify-details">{{ $notif['title'] ?? 'Notification' }}
                                    @if(isset($notif['time']))
                                        <small class="text-muted">{{ $notif['time'] }}</small>
                                    @endif
                                </p>
                                @if(isset($notif['message']))
                                    <p class="text-muted mb-0 user-msg">
                                        <small>{{ $notif['message'] }}</small>
                                    </p>
                                @endif
                            </a>
                        @empty
                            <span class="dropdown-item text-muted">Aucune notification</span>
                        @endforelse
                    </div>

                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary notify-item notify-all">
                        Voir toutes les notifications <i class="fe-arrow-right"></i>
                    </a>
                </div>
            </li>

            {{-- Utilisateur --}}
            <li>
                <a class="nav-link nav-user me-0 waves-light" href="{{ route('profil.show') }}" role="button">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=100&background=4e54c8&color=fff" class="rounded-circle" alt="Avatar">
                    <span class="pro-user-name ms-1">
                        {{ Auth::user()->name }}
                    </span>
                </a>
            </li>

            {{-- Paramètres --}}
            <li class="notification-list ms-2">
                <a href="javascript:void(0);" class="nav-link waves-light">
                    <i class="fe-settings noti-icon"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

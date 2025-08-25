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
            @if (!Auth::user()->hasRole('ADMIN'))
            {{-- Notifications --}}
            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fe-bell noti-icon"></i>
                   @if(isset($notifications) && count($notifications) > 0)
                        <span id="notif-badge" class="badge bg-danger rounded-circle noti-icon-badge">{{ count($notifications) }}</span>
                    @endif

                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                    <!-- Titre -->
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">
                            <span class="float-end">
                                <a href="" class="text-dark">
                                    <small>Tout effacer</small>
                                </a>
                            </span>
                            Notifications
                        </h5>
                    </div>

                    <!-- Notifications list -->
                    <div class="noti-scroll" data-simplebar style="max-height: 300px;">
                        @forelse ($notifications as $notif)
                            <a href="{{ route('notifications.read', $notif['id']) }}" class="dropdown-item notify-item @if(isset($notif['unread']) && $notif['unread']) active @endif">
                                <div class="notify-icon {{ $notif['bg'] ?? 'bg-primary' }}">
                                    @if(isset($notif['image']))
                                        <img src="{{ asset($notif['image']) }}" class="img-fluid rounded-circle" alt="" />
                                    @else
                                        <i class="{{ $notif['icon'] ?? 'mdi mdi-bell-outline' }}"></i>
                                    @endif
                                </div>
                                <p class="notify-details">
                                    {{ $notif['title'] ?? 'Notification' }}
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

                    <!-- Voir toutes -->
                    <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary notify-item notify-all">
                        Voir toutes les notifications <i class="fe-arrow-right"></i>
                    </a>

                </div>
            @endif
            </li>
            {{-- Utilisateur --}}
            <li class="dropdown notification-list topbar-dropdown">
                    <a  class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="{{ route('profil.show') }}"  role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=100&background=4e54c8&color=fff" class="rounded-circle" alt="Avatar">
                        <span class="pro-user-name ms-1">
                            {{ Auth::user()->name }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Bienvenu !</h6>
                        </div>

                        <!-- item-->
                        <a href="{{ route('profil.show') }}" class="dropdown-item notify-item">
                            <i class="fe-user"></i>
                            <span>Mon profil</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();" class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span> Déconnexion </span>
                        </a>
                        <form id="logout-form-navbar" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </li>

            {{-- Paramètres --}}
            <li class="dropdown notification-list">
                <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                    <i class="fe-settings noti-icon"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

{{-- Barre latérale des paramètres --}}
<div class="right-bar">
    <div data-simplebar class="h-100">
        <div class="tab-pane active" id="settings-tab" role="tabpanel">
            <h6 class="fw-medium px-3 m-0 py-2 font-13 text-uppercase bg-light">
                <span class="d-block py-1">Paramètres de l'application</span>
            </h6>

            <div class="p-3">
                <div class="alert alert-warning" role="alert">
                    <strong>Personnalisez</strong> les couleurs, les menus, la langue, etc.
                </div>

                {{-- Schéma de couleurs --}}
                <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Schéma de couleurs</h6>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="layout-color" value="light" id="light-mode-check" checked />
                    <label class="form-check-label" for="light-mode-check">Mode clair</label>
                </div>

                {{-- Largeur --}}
                <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Largeur de l'affichage</h6>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="layout-width" value="fluid" id="fluid-check" checked />
                    <label class="form-check-label" for="fluid-check">Fluide</label>
                </div>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="layout-width" value="boxed" id="boxed-check" />
                    <label class="form-check-label" for="boxed-check">Encadré</label>
                </div>

                {{-- Position des menus --}}
                <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Position des menus</h6>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="menu-position" value="fixed" id="fixed-check" checked />
                    <label class="form-check-label" for="fixed-check">Fixé</label>
                </div>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="menu-position" value="scrollable" id="scrollable-check" />
                    <label class="form-check-label" for="scrollable-check">Défilable</label>
                </div>

                {{-- Couleur de la barre latérale --}}
                <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Couleur de la barre latérale</h6>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="leftbar-color" value="light" id="light-check" />
                    <label class="form-check-label" for="light-check">Clair</label>
                </div>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="leftbar-color" value="dark" id="dark-check" checked />
                    <label class="form-check-label" for="dark-check">Sombre</label>
                </div>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="leftbar-color" value="brand" id="brand-check" />
                    <label class="form-check-label" for="brand-check">Couleur de marque</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input" name="leftbar-color" value="gradient" id="gradient-check" />
                    <label class="form-check-label" for="gradient-check">Dégradé</label>
                </div>

                {{-- Taille barre latérale --}}
                <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Taille de la barre latérale</h6>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="leftbar-size" value="default" id="default-size-check" checked />
                    <label class="form-check-label" for="default-size-check">Par défaut</label>
                </div>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="leftbar-size" value="condensed" id="condensed-check" />
                    <label class="form-check-label" for="condensed-check">Condensée</label>
                </div>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="leftbar-size" value="compact" id="compact-check" />
                    <label class="form-check-label" for="compact-check">Compacte</label>
                </div>

                {{-- Topbar --}}
                <h6 class="fw-medium font-14 mt-4 mb-2 pb-1">Barre supérieure</h6>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="topbar-color" value="dark" id="darktopbar-check" checked />
                    <label class="form-check-label" for="darktopbar-check">Sombre</label>
                </div>
                <div class="form-check form-switch mb-1">
                    <input type="checkbox" class="form-check-input" name="topbar-color" value="light" id="lighttopbar-check" />
                    <label class="form-check-label" for="lighttopbar-check">Clair</label>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.notify-item').forEach(function (item) {
        item.addEventListener('click', function () {
            const badge = document.getElementById('notif-badge');
            if (badge) {
                let count = parseInt(badge.textContent.trim());
                if (count > 1) {
                    badge.textContent = count - 1;
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    });
});
</script>

<div class="left-side-menu">
    <div class="h-100" data-simplebar>
        <!-- User box -->
        <div class="user-box text-center">
            <img src="{{ asset('assets/images/users/user-1.jpg') }}" alt="user-img" title="User" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="#" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu user-pro-dropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i> <span>Mon profil</span>
                    </a>
                    <a href="#" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i> <span>Paramètres</span>
                    </a>
                    <a href="#" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i> <span>Verrouiller</span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fe-log-out me-1"></i> <span>Déconnexion</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
            <p class="text-muted mb-0">
                @foreach(Auth::user()->roles as $role)
                    {{ $role->name }}@if(!$loop->last), @endif
                @endforeach
            </p>
        </div>

        <!--- Sidebar menu -->
        <div id="sidebar-menu">
            <ul id="side-menu">
                <li class="menu-title">Navigation</li>

                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i data-feather="airplay"></i>
                        <span> Tableau de bord </span>
                    </a>
                </li>

                <!-- RH -->
                @if (Auth::user()->hasRole('RH'))
                    <li class="menu-title mt-2">RH</li>
                    <li><a href="{{ route('offres.index') }}"><i data-feather="file-text"></i> <span> Offres </span></a></li>
                    <li><a href="#"><i data-feather="users"></i> <span> Tuteurs </span></a></li>
                    <li><a href="{{ route('entretiens.index') }}"><i data-feather="clipboard"></i> <span> Entretiens </span></a></li>
                    <li><a href="{{ route('entretiens.calendrier') }}"><i data-feather="calendar"></i> <span> Calendrier </span></a></li>
                @endif

                <!-- ADMIN -->
                @if (Auth::user()->hasRole('ADMIN'))
                    <li class="menu-title mt-2">Administration</li>
                    <li>
                        <a href="#menu-admin" data-bs-toggle="collapse" class="{{ request()->routeIs('roles.*') || request()->routeIs('user.*') ? 'active' : '' }}">
                            <i data-feather="user"></i> <span> Utilisateurs & Rôles </span> <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse {{ request()->routeIs('roles.*') || request()->routeIs('user.*') ? 'show' : '' }}" id="menu-admin">
                            <ul class="nav-second-level">
                                <li><a href="{{ route('roles.index') }}">Rôles</a></li>
                                <li><a href="{{ route('user.index') }}">Utilisateurs</a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <!-- DIRECTEUR -->
                @if (Auth::user()->hasRole('DIRECTEUR'))
                    <li class="menu-title mt-2">Directeur</li>
                    <li><a href="#"><i data-feather="file-text"></i> <span> Gérer Rapports </span></a></li>
                    <li><a href="#"><i data-feather="database"></i> <span> Gérer Données </span></a></li>
                @endif

                <!-- TUTEUR -->
                @if (Auth::user()->hasRole('TUTEUR'))
                    <li class="menu-title mt-2">Tuteur</li>
                    <li><a href="#"><i data-feather="briefcase"></i> <span> Gérer Stage </span></a></li>
                    <li><a href="#"><i data-feather="file-text"></i> <span> Gérer Rapports </span></a></li>
                @endif

                <!-- Autres rôles (sauf ADMIN) -->
                @if (!Auth::user()->hasRole('ADMIN'))
                    <li class="menu-title mt-2">Gestion</li>
                    <li>
                        <a href="#menu-stages" data-bs-toggle="collapse">
                            <i data-feather="briefcase"></i> <span> Stages </span> <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menu-stages">
                            <ul class="nav-second-level">
                                <li><a href="#">Académique</a></li>
                                <li><a href="#">Professionnel</a></li>
                                <li><a href="#">Pré-embauche</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#menu-documents" data-bs-toggle="collapse">
                            <i data-feather="clipboard"></i> <span> Rapports & Documents </span> <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menu-documents">
                            <ul class="nav-second-level">
                                <li><a href="#">Rapports des Tuteurs</a></li>
                                <li><a href="#">Rapports des Stagiaires</a></li>
                                <li><a href="#">Attestations</a></li>
                                <li><a href="#">Conventions</a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#"><i data-feather="mail"></i> <span> Notifications </span></a>
                    </li>
                @endif

                  <!-- 🔴 Bouton Déconnexion -->
                <li class="mt-3">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i data-feather="log-out"></i>
                        <span> Déconnexion </span>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
</div>

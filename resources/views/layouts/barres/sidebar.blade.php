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

        <!-- Sidebar menu -->
        <div id="sidebar-menu">
            <ul id="side-menu">

                <!-- Tableau de bord spécifique -->
                @if (Auth::user()->hasRole('ADMIN'))
                    <li class="menu-title">ADMIN</li>
                    <li>
                        <a href="{{route('dashboard')}}"><i data-feather="airplay"></i><span> Tableau de bord </span></a>
                    </li>
                    <li class="menu-title mt-2">Administration</li>
                    <li>
                        <a href="#menu-admin" data-bs-toggle="collapse" class="{{ request()->routeIs('roles.*') || request()->routeIs('user.*') ? 'active' : '' }}">
                            <i data-feather="user"></i> <span> Utilisateurs & Rôles </span> <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" {{ request()->routeIs('roles.*') || request()->routeIs('user.*') ? 'show' : '' }} id="menu-admin">
                            <ul class="nav-second-level">
                                <li><a href="{{ route('roles.index') }}">Rôles</a></li>
                                <li><a href="{{ route('user.index') }}">Utilisateurs</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="{{ route('departements.index') }}"><i data-feather="grid"></i> <span>Départements </span></a></li>
                    <li><a href="{{ route('rapport.form') }}"><i data-feather="bar-chart-2"></i> <span> Exports </span></a></li>




                @elseif (Auth::user()->hasRole('RH'))
                    <li class="menu-title">RH</li>
                    <!-- DASHBOARD -->
                    <li>
                        <a href="{{ route('dashboard.RH') }}"><i data-feather="airplay"></i><span> Tableau de bord </span></a>
                    </li>

                    <!-- OFFRES -->
                    <li class="menu-title mt-2">OFFRES</li>
                    <li><a href="{{ route('offres.index') }}"><i data-feather="file-text"></i> <span> Mes Offres </span></a></li>
                    <li class="menu-title mt-2">CANDIDATURES</li>
                    <li><a href="{{ route('candidatures.index') }}"><i data-feather="users"></i> <span> Candidatures </span></a></li>
                    <li><a href="{{ route('admin.candidatures.spontanees.index') }}"><i data-feather="users"></i> <span> Candidatures Libre </span></a></li>
                    <!-- PLANIFICATION -->
                    <li class="menu-title mt-2">PLANIFICATION</li>
                    <li><a href="{{ route('entretiens.index') }}"><i data-feather="clipboard"></i> <span> Entretiens </span></a></li>
                    <li><a href="{{ route('entretiens.calendrier') }}"><i data-feather="calendar"></i> <span> Calendrier </span></a></li>
                    <li><a href="{{ route('candidatures.retenus') }}"><i data-feather="plus-circle"></i> <span> Planifier Entretien </span></a></li>

                    <!-- STAGES -->
                    <li class="menu-title mt-2">STAGES</li>
                    <li><a href="{{ route('rh.stages.attente_tuteur') }}"><i data-feather="user-plus"></i> <span> Affectation Tuteur </span></a></li>
                    <li><a href="{{ route('rh.stages.en_cours') }}"><i data-feather="briefcase"></i> <span> Stages en cours </span></a></li>
                    <li><a href="{{ route('rh.stages.termines') }}"><i data-feather="briefcase"></i> <span> Stages terminés </span></a></li>
                    <!-- RAPPORTS -->
                    <li class="menu-title mt-2">RAPPORTS</li>
                    <li><a href="{{ route('stages.rh.candidats_en_stage') }}"><i data-feather="list"></i> <span> Liste Candidats </span></a></li>
                    <li><a href="{{ route('entretiens.liste') }}"><i data-feather="file"></i> <span> Liste Entretiens </span></a></li>
                    <li><a href="{{ route('rapport.form') }}"><i data-feather="bar-chart-2"></i> <span> Synthèse </span></a></li>
                    <li><a href="{{ route('attestations.liste') }}"><i data-feather="activity"></i> <span> Attestation </span></a></li>

                @elseif (Auth::user()->hasRole('DIRECTEUR'))
                    <li class="menu-title">DIRECTEUR</li>
                    <li>
                        <a href="{{ route('dashboard.directeur') }}"><i data-feather="airplay"></i><span> Tableau de bord </span></a>
                    </li>
                    <li class="menu-title">TUTEUR</li>
                    <li><a href="{{ route('directeur.tuteurs') }}"><i data-feather="users"></i> <span> Mes Tuteurs </span></a></li>
                    <li><a href="{{ route('directeur.stages') }}"><i data-feather="user-check"></i> <span> Attribuer Tuteur @if(!empty($nombreStagesAttente) && $nombreStagesAttente > 0)
                    <span class="badge bg-danger ms-2">{{ $nombreStagesAttente }}</span>@endif </span></a></li>

                    <li class="menu-title mt-2">STAGES</li>
                    <li><a href="{{ route('stages.candidats_en_cours') }}"><i data-feather="file-text"></i> <span> Candidats </span></a></li>
                    <li><a href="{{ route('stages.en_cours') }}"><i data-feather="database"></i> <span> Stages en cours </span></a></li>
                    <li><a href="{{ route('stages.termines') }}"><i data-feather="check-circle"></i> <span> Stages terminés </span></a></li>

                    <li class="menu-title mt-2">RAPPORTS</li>
                    <li><a href="{{ route('directeur.formulaires.liste') }}"><i data-feather="file"></i> <span> Rapports </span></a></li>
                    <li><a href="{{ route('rapport.form') }}"><i data-feather="bar-chart-2"></i> <span> Synthèse </span></a></li>

                @elseif (Auth::user()->hasRole('TUTEUR'))
                    <li class="menu-title">TUTEUR</li>
                    <li><a href="{{ route('dashboard.tuteur') }}"><i data-feather="airplay"></i> <span> Tableau de bord </span></a></li>
                    <li class="menu-title mt-2">STAGES</li>
                    <li><a href="{{ route('stages.candidats_tuteurs') }}"><i data-feather="file-text"></i> <span>Liste des candidats </span></a></li>
                    <li><a href="{{ route('tuteur.stages.en_cours') }}"><i data-feather="briefcase"></i> <span> Stages en cours </span></a></li>
                    <li><a href="{{ route('tuteur.stages.termines') }}"><i data-feather="check-circle"></i> <span> Stages terminés </span></a></li>
                    <li class="menu-title mt-2">RAPPORTS</li>
                    <li><a href="{{ route('tuteur.formulaires.affichage') }}"><i data-feather="activity"></i> <span> Rapports candidats </span></a></li>
                @else
                    <li class="menu-title">Navigation</li>
                    <li><a href="{{ route('dashboard') }}"><i data-feather="airplay"></i> <span> Tableau de bord </span></a></li>
                @endif

                <!-- Déconnexion
                <li class="mt-3">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i data-feather="log-out"></i>
                        <span> Déconnexion </span>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>-->
            </ul>
        </div>

        <!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
</div>

@extends('layouts.home')

@section('content')
<div class="container mt-4">

    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DIPRH</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Calendrier des entretiens</li>
                </ol>
            </nav>
            <a href="{{ route('entretiens.create') }}" class="btn btn-primary btn-sm">
                <i class="mdi mdi-calendar-clock"></i> Planifier un entretien
            </a>
        </div>
    </div>

    <!-- Messages flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <!-- Entretiens table -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Liste des entretiens</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Candidat</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($entretiens as $entretien)
                            <tr>
                                <td>{{ $entretien->titre ?? 'Sans titre' }}</td>
                                <td>{{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</td>
                                <td>{{ $entretien->candidat->nom }} {{ $entretien->candidat->prenoms }}</td>
                                <td class="text-center">
                                    @switch($entretien->statut)
                                        @case('prevu')
                                            <span class="badge bg-info">Prévu</span>
                                            @break
                                        @case('en_cours')
                                            <span class="badge bg-warning text-dark">En cours</span>
                                            @break
                                        @case('termine')
                                            <span class="badge bg-success">Terminé</span>
                                            @break
                                        @case('annule')
                                            <span class="badge bg-danger">Annulé</span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @if ($entretien->statut === 'prevu')
                                        <form action="{{ route('entretiens.start', $entretien->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Confirmer le début de cet entretien ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Commencer l'entretien">
                                                <i class="mdi mdi-play-circle"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('entretiens.annuler', $entretien->id) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Confirmer l\'annulation de cet entretien ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Annuler l'entretien">
                                                <i class="mdi mdi-cancel"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('entretiens.show', $entretien->id) }}" class="btn btn-sm btn-outline-primary" title="Voir détails">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Aucun entretien trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

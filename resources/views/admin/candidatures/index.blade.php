@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
             <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="">DIPRH</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Liste des offres</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('candidatures.index') }}">Liste des candidatures</a></li>

                                        </ol>
                                    </div>
                                </div>
                                <h4 class="mb-4">Liste des candidatures pour l'offre : <strong>{{ $offre->titre ?? '' }}</strong></h4>
                            </div>
                        </div>
                             

            @if($offre->candidatures->isEmpty())
                <div class="alert alert-info">
                    Aucune candidature enregistrée.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-centered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Candidat</th>
                                <th>Statut</th>
                                <th>Date de soumission</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offre->candidatures as $candidature)
                                @php
                                    $statut = $candidature->statut;
                                    $labels = [
                                        'en_cours' => 'warning',
                                        'retenu' => 'success',
                                        'valide' => 'primary',
                                        'rejete' => 'danger',
                                        'effectuee' => 'info',
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</td>
                                    <td>
                                        <span class="badge bg-{{ $labels[$statut] ?? 'secondary' }}">
                                            {{ \App\Models\Candidature::STATUTS[$statut] ?? ucfirst($statut) }}
                                        </span>
                                    </td>
                                    <td>{{ $candidature->date_soumission->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-sm btn-outline-info me-1" title="Voir">
                                            <i class="mdi mdi-eye"></i>
                                        </a>

                                        @if($statut === 'en_cours')
                                            <form action="{{ route('candidatures.retenir', $candidature->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-success me-1" type="submit" title="Retenir">
                                                    <i class="mdi mdi-check-circle-outline"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('candidatures.reject', $candidature->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-danger" type="submit" title="Rejeter">
                                                    <i class="mdi mdi-close-circle-outline"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($statut === 'retenu')
                                            <form action="{{ route('candidatures.valider', $candidature->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-primary" type="submit" title="Valider">
                                                    <i class="mdi mdi-check-decagram-outline"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($statut === 'valide')
                                            <form action="{{ route('candidatures.effectuee', $candidature->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-info" type="submit" title="Marquer effectuée">
                                                    <i class="mdi mdi-calendar-check-outline"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

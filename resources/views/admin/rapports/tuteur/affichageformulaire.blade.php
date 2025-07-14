@extends('layouts.home')

@section('content')
<div class="container mt-4">
 <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.tuteur') }}">DIPRH</a></li>
                                <li class="breadcrumb-item active">Rapports</li>
                            </ol>
                        </div>
                        <h4 class="page-title"><strong>Rpports d'évalutation sur les stages</strong></h4>
                    </div>
                </div>
            </div>
                @if($stages->isEmpty())
                    <div class="alert alert-info">Aucun stage en cours pour le moment.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Stagiaire</th>
                                    <th>Offre</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Formulaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stages as $stage)
                                <tr>
                                    <td>
                                        {{ $stage->candidature->candidat->nom ?? 'N/A' }}
                                        {{ $stage->candidature->candidat->prenoms ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $stage->candidature->offre->titre ?? '---' }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') ?? '---' }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') ?? '---' }}
                                    </td>
                                    <td class="text-center">
                                        @if($stage->formulaire)
                                            <a href="{{ route('tuteur.formulaires.details', $stage->formulaire->id) }}" class="btn btn-sm btn-primary">
                                                Évaluer
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Aucun formulaire</span>
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
</div>
@endsection

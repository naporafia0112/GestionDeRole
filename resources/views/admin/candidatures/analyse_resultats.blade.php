@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">DIPRH</a></li>
                        <li class="breadcrumb-item"><a href="">Candidatures</a></li>
                        <li class="breadcrumb-item active"><strong>Analyse IA</strong></li>
                    </ol>
                </div>
                <h4 class="page-title"><strong>Analyse IA des candidatures</strong></h4>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <strong>Critères utilisés :</strong> {{ $prompt ?? 'Non précisé' }}
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            Résultat de l’analyse IA
        </div>
        <div class="card-body">
            <p style="white-space: pre-line;">{{ $analyse }}</p>
        </div>
    </div>
</div>
@endsection

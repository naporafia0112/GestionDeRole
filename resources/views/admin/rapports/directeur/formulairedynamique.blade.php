@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.tuteur') }}">DIPRH</a></li>
                                <li class="breadcrumb-item active">
                                    <a href="{{ route('directeur.formulaires.liste') }}">Listes des formulaires crées</a>
                                </li>
                                <li class="breadcrumb-item active">Formulaire de création</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Créer un nouveau formulaire</h4>
                        <p class="sub-header">Définissez les champs et les détails de votre formulaire</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Veuillez corriger les erreurs ci-dessous :</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('formulaires.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre du formulaire <span class="text-danger">*</span></label>
                                    <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stage_id" class="form-label">Associer à un stage  <span class="text-danger">*</span></label>
                                    <select name="stage_id" id="stage_id" class="form-select @error('stage_id') is-invalid @enderror">
                                        <option value="">-- Aucun --</option>
                                        @foreach ($stages as $stage)
                                            <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>
                                                {{ $stage->candidature->candidat->nom ?? 'Nom inconnu' }} {{ $stage->candidature->candidat->prenoms ?? '' }} - {{ $stage->candidature->offre->titre ?? 'Offre inconnue' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('stage_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <h5>Champs du formulaire <span class="text-danger">*</span></h5>
                                <div id="champs-container"></div>

                                <button type="button" onclick="ajouterChamp()" class="btn btn-secondary mt-2 mb-3">
                                    <i data-feather="plus" class="me-1"></i> Ajouter un champ
                                </button>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        Créer le formulaire
                                    </button>
                                    <a href="{{ url()->previous() }}" class="btn btn-light ms-2">Annuler</a>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container-fluid -->
    </div> <!-- end content -->
</div> <!-- end container -->

@push('scripts')
<script>
    let index = 0;

    function ajouterChamp() {
        const container = document.getElementById('champs-container');

        const html = `
        <div class="border rounded p-3 mb-3">
            <div class="mb-3">
                <label class="form-label">Label <span class="text-danger">*</span></label>
                <input type="text" name="champs[${index}][label]" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select name="champs[${index}][type]" class="form-select" required>
                    <option value="text">Texte</option>
                    <option value="textarea">Zone de texte</option>
                    <option value="number">Nombre</option>
                    <option value="date">Date</option>
                    <option value="checkbox">Case à cocher</option>
                    <option value="select">Liste déroulante</option>
                </select>
            </div>
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="champs[${index}][requis]" value="1" id="requis_${index}">
                <label class="form-check-label" for="requis_${index}">Champ requis</label>
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', html);
        index++;

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
</script>
@endpush

@endsection

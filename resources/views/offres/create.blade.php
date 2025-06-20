@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href={{ route('offres.index') }}>Offres</a></li>
                                <li class="breadcrumb-item active">{{ isset($offre) ? 'Modifier' : 'Créer' }} une offre</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Formulaire d'offre</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">{{ isset($offre) ? 'Modifier' : 'Créer' }} une offre</h4>
                            <p class="sub-header">Remplissez les détails de l'offre</p>

                            <form action="{{ isset($offre) ? route('offres.update', $offre->id) : route('offres.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($offre))
                                    @method('PUT')
                                @endif
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                                @endif

                                @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                                @endif
                                <!-- Titre de l'offre -->
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre de l'offre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="titre" name="titre"
                                        placeholder="Ex: Développeur Web Senior"
                                        value="{{ old('titre', $offre->titre ?? '') }}" required>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description"
                                        rows="3" required>{{ old('description', $offre->description ?? '') }}</textarea>
                                </div>


                                <!-- Localisation -->
                                <div class="mb-3">
                                    <label for="localisation_id" class="form-label">Localisation <span class="text-danger">*</span></label>
                                    <select id="localisation_id" name="localisation_id" class="form-select" required>
                                        <option value="">-- Choisissez une localisation --</option>
                                        @foreach($localisations as $loc)
                                            <option value="{{ $loc->id }}"
                                                {{ old('localisation_id') == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->pays }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="date_publication" class="form-label">Date publication</label>
                                    <input type="date" class="form-control" id="date_publication" name="date_publication"
                                        value="{{ old('date_publication', isset($offre->date_publication) ? $offre->date_publication->format('Y-m-d') : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="exigences" class="form-label">Exigences <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="exigences" name="exigences"
                                        rows="3" required>{{ old('exigences', $offre->exigences ?? '') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="departement" class="form-label">Départements <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="departement" name="departement"
                                        rows="1" required>{{ old('departement', $offre->departement ?? '') }}</textarea>
                                </div>
                                <!-- Fichier PDF -->
                                <div class="mb-3">
                                    <label for="fichier" class="form-label">Joindre un fichier PDF</label>
                                    <input type="file" class="form-control" id="fichier" name="fichier" accept=".pdf">
                                </div>
                                <div class="mb-3">
                                    <label for="date_limite" class="form-label">Date limite <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_limite" name="date_limite"
                                        value="{{ old('date_limite', isset($offre->date_limite) ? $offre->date_limite->format('Y-m-d') : '') }}" required>
                                </div>

                                @if(isset($offre))
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="est_publie" name="est_publie"
                                            {{ old('est_publie', $offre->est_publie) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="est_publie">Publier cette offre</label>
                                    </div>
                                </div>
                                @endif

                                <!-- Boutons de soumission -->
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($offre) ? 'Mettre à jour' : 'Enregistrer' }}
                                    </button>
                                    <a href="{{ route('offres.index') }}" class="btn btn-light">Annuler</a>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialisation des éditeurs de texte ou autres scripts si nécessaire
    $(document).ready(function() {
        // Exemple: initialisation d'un éditeur de texte riche
        // $('#description').summernote();
    });
</script>
@endpush

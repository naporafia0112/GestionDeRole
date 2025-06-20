@extends('layouts.home')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')

<div class="container mt-4">
    <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
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
                                <p class="sub-header">Modifiez les détails de l'offre</p>

                                <form action="{{ route('offres.update', $offre->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    @if(session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
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

                                    <!-- Titre -->
                                    <div class="mb-3">
                                        <label for="titre">Titre *</label>
                                        <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                                            value="{{ old('titre', $offre->titre) }}" required>
                                        @error('titre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-3">
                                        <label for="description">Description *</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="3" required>{{ old('description', $offre->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Localisation -->
                                    <div class="mb-3">
                                        <label for="localisation_id">Localisation *</label>
                                        <select name="localisation_id" class="form-select @error('localisation_id') is-invalid @enderror" required>
                                            @foreach($localisations as $loc)
                                                <option value="{{ $loc->id }}" {{ old('localisation_id', $offre->localisation_id) == $loc->id ? 'selected' : '' }}>
                                                    {{ $loc->pays }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('localisation_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Date de publication -->
                                    <div class="mb-3">
                                        <label for="date_publication">Date de publication *</label>
                                        <input type="date" name="date_publication" class="form-control @error('date_publication') is-invalid @enderror"
                                            value="{{ old('date_publication', optional($offre->date_publication)->format('Y-m-d')) }}" required>
                                        @error('date_publication')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Date limite -->
                                    <div class="mb-3">
                                        <label for="date_limite">Date limite *</label>
                                        <input type="date" name="date_limite" class="form-control @error('date_limite') is-invalid @enderror"
                                            value="{{ old('date_limite', optional($offre->date_limite)->format('Y-m-d')) }}" required>
                                        @error('date_limite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Exigences -->
                                    <div class="mb-3">
                                        <label for="exigences">Exigences *</label>
                                        <textarea name="exigences" class="form-control @error('exigences') is-invalid @enderror"
                                            rows="3" required>{{ old('exigences', $offre->exigences) }}</textarea>
                                        @error('exigences')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Département -->
                                    <div class="mb-3">
                                        <label for="departement">Département *</label>
                                        <input type="text" name="departement" class="form-control @error('departement') is-invalid @enderror"
                                            value="{{ old('departement', $offre->departement) }}" required>
                                        @error('departement')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Fichier PDF -->
                                    <div class="mb-3">
                                        <label for="fichier">Fichier PDF (optionnel)</label>
                                        @if($offre->fichier && Storage::disk('public')->exists($offre->fichier))
                                            <p>
                                                Fichier actuel :
                                                <a href="{{ asset('storage/' . $offre->fichier) }}" target="_blank">Voir</a>
                                                ({{ round(Storage::disk('public')->size($offre->fichier) / 1024) }} KB)
                                            </p>
                                        @endif
                                        <input type="file" name="fichier" class="form-control @error('fichier') is-invalid @enderror" accept="application/pdf">
                                        @error('fichier')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Publier
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="est_publie" id="est_publie" class="form-check-input"
                                            {{ old('est_publie', $offre->est_publie) ? 'checked' : '' }}>
                                        <label for="est_publie" class="form-check-label">Publier cette offre</label>
                                    </div>-->

                                    <!-- Boutons -->
                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                    <a href="{{ route('offres.index') }}" class="btn btn-secondary">Annuler</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

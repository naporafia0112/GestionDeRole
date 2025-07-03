@extends('layouts.vitrine.vitrine')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-4">Postuler pour l'offre : {{ $offre->titre ?? 'Offre' }}</h4>

                    <form action="{{ route('candidature.store', $offre->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6">

                                {{-- Nom --}}
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-control @error('nom') is-invalid @enderror" >
                                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Prénoms --}}
                                <div class="mb-3">
                                    <label for="prenoms" class="form-label">Prénoms <span class="text-danger">*</span></label>
                                    <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms') }}" class="form-control @error('prenoms') is-invalid @enderror" >
                                    @error('prenoms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" >
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Téléphone --}}
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone</label>
                                    <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}" class="form-control @error('telephone') is-invalid @enderror">
                                    @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Quartier --}}
                                <div class="mb-3">
                                    <label for="quartier" class="form-label">Quartier <span class="text-danger">*</span></label>
                                    <input type="text" id="quartier" name="quartier" value="{{ old('quartier') }}" class="form-control @error('quartier') is-invalid @enderror">
                                    @error('quartier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>



                            </div> <!-- end col -->

                            <div class="col-lg-6">

                                 {{-- Ville --}}
                                <div class="mb-3">
                                    <label for="ville" class="form-label">Ville <span class="text-danger">*</span></label>
                                    <input type="text" id="ville" name="ville" value="{{ old('ville') }}" class="form-control @error('ville') is-invalid @enderror">
                                    @error('ville') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                {{-- Type de dépôt --}}
                                <div class="mb-3">
                                    <label for="type_depot" class="form-label">Type de dépôt <span class="text-danger">*</span></label>
                                    <select id="type_depot" name="type_depot" class="form-select @error('type_depot') is-invalid @enderror" >
                                        <option value="">-- Sélectionner --</option>
                                        <option value="stage professionnel" {{ old('type_depot') == 'stage professionnel' ? 'selected' : '' }}>Stage professionnel</option>
                                        <option value="stage académique" {{ old('type_depot') == 'stage académique' ? 'selected' : '' }}>Stage académique</option>
                                        <option value="stage de préembauche" {{ old('type_depot') == 'stage de préembauche' ? 'selected' : '' }}>Stage de préembauche</option>
                                    </select>
                                    @error('type_depot') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- CV --}}
                                <div class="mb-3">
                                    <label for="cv_fichier" class="form-label">CV (prénom_nom.pdf) <span class="text-danger">*</span></label>
                                    <input type="file" id="cv_fichier" name="cv_fichier" class="form-control @error('cv_fichier') is-invalid @enderror">
                                    @error('cv_fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Lettre de motivation --}}
                                <div class="mb-3">
                                    <label for="lm_fichier" class="form-label">Lettre de motivation(prénom_nom.pdf) <span class="text-danger">*</span></label>
                                    <input type="file" id="lm_fichier" name="lm_fichier" class="form-control @error('lm_fichier') is-invalid @enderror">
                                    @error('lm_fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Lettre de recommandation --}}
                                <div class="mb-3">
                                    <label for="lr_fichier" class="form-label">Lettre de recommandation(prénom_nom.pdf)</label>
                                    <input type="file" id="lr_fichier" name="lr_fichier" class="form-control @error('lr_fichier') is-invalid @enderror">
                                    @error('lr_fichier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="text-end mt-4">
                                    <a href="{{ route('vitrine.show', $offre->id) }}" class="btn btn-sm btn-link">
                                        <i class="bi bi-arrow-left"></i> Retour
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-send"></i> Soumettre la candidature
                                    </button>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </form>

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col-12 -->
    </div> <!-- end row -->
</div> <!-- end container -->
@endsection

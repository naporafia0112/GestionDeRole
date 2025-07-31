
@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Modifier le formulaire</h4>
                                <p class="sub-header">Modifiez le titre et les champs de votre formulaire</p>
                            </div>
                        </div>
                    </div>

                    <form id="formulaire-edit" action="{{ route('formulaires.update', $formulaire->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre du formulaire</label>
                            <input type="text" name="titre" class="form-control" value="{{ old('titre', $formulaire->titre) }}" required>
                        </div>

                        <hr>
                        <h5>Champs du formulaire existants</h5>

                        @foreach($formulaire->champs as $i => $champ)
                            <div class="border rounded p-3 mb-3 champ-item">
                                <input type="hidden" name="champs[{{ $i }}][id]" value="{{ $champ->id }}">
                                <div class="mb-3">
                                    <label class="form-label">Label</label>
                                    <input type="text" name="champs[{{ $i }}][label]" class="form-control" value="{{ $champ->label }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="champs[{{ $i }}][type]" class="form-select type-select" required>
                                        <option value="text" {{ $champ->type == 'text' ? 'selected' : '' }}>Texte</option>
                                        <option value="textarea" {{ $champ->type == 'textarea' ? 'selected' : '' }}>Zone de texte</option>
                                        <option value="number" {{ $champ->type == 'number' ? 'selected' : '' }}>Nombre</option>
                                        <option value="date" {{ $champ->type == 'date' ? 'selected' : '' }}>Date</option>
                                        <option value="checkbox" {{ $champ->type == 'checkbox' ? 'selected' : '' }}>Case à cocher</option>
                                        <option value="select" {{ $champ->type == 'select' ? 'selected' : '' }}>Liste déroulante</option>
                                        <option value="file" {{ $champ->type == 'file' ? 'selected' : '' }}>Fichier</option>
                                    </select>
                                </div>
                                <div class="mb-3 options-container" style="{{ in_array($champ->type, ['select','checkbox']) ? '' : 'display:none;' }}">
                                    <label class="form-label">Options</label>
                                    <textarea name="champs[{{ $i }}][options]" class="form-control" rows="3">{{ $champ->options }}</textarea>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="champs[{{ $i }}][requis]" value="1" {{ $champ->requis ? 'checked' : '' }}>
                                    <label class="form-check-label">Champ requis</label>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="{{ route('directeur.formulaires.liste') }}" class="btn btn-light ms-2">Annuler</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

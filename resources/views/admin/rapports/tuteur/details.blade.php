@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <!-- start page title -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('dashboard.tuteur') }}">DIPRH</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('tuteur.formulaires.affichage') }}">Rapports</a></li>
                                            <li class="breadcrumb-item active">Formulaire d'évaluation</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">{{ $formulaire->titre }}</h4>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('tuteur.formulaires.affichage') }}" class="btn btn-sm btn-link">
                                        <i class="mdi mdi-keyboard-backspace"></i> Retour
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- erreurs globales --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <strong>Veuillez corriger les erreurs ci-dessous :</strong>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="formulaire-evaluation" action="{{ route('tuteur.formulaires.store', $formulaire) }}" method="POST">
                            @csrf

                            <!-- Sélection du stage -->
                            <div class="mb-4">
                                <label for="stage_id" class="form-label">Stage concerné <span class="text-danger">*</span></label>
                                <select name="stage_id" id="stage_id" class="form-select @error('stage_id') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez un stage --</option>
                                    @foreach(Auth::user()->stages as $stage)
                                        <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? 'selected' : '' }}>
                                            {{ $stage->candidat->nom ?? 'Nom inconnu' }} {{ $stage->candidat->prenoms ?? '' }} - {{ $stage->candidature->offre->titre ?? 'Offre inconnue' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('stage_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Champs dynamiques -->
                            @foreach ($formulaire->champs as $champ)
                                <div class="mb-3">
                                    <label class="form-label">{{ $champ->label }}
                                        @if($champ->requis)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                    @php
                                        $name = "champs[{$champ->id}]";
                                        $required = $champ->requis ? 'required' : '';
                                        $options = $champ->options ? array_map('trim', explode(',', $champ->options)) : [];
                                    @endphp

                                    @switch($champ->type)
                                        @case('text')
                                            <input type="text" name="{{ $name }}" class="form-control" {{ $required }}>
                                            @break

                                        @case('textarea')
                                            <textarea name="{{ $name }}" class="form-control" rows="3" {{ $required }}></textarea>
                                            @break

                                        @case('number')
                                            <input type="number" name="{{ $name }}" class="form-control" {{ $required }}>
                                            @break

                                        @case('date')
                                            <input type="date" name="{{ $name }}" class="form-control" {{ $required }}>
                                            @break

                                        @case('select')
                                            <select name="{{ $name }}" class="form-select" {{ $required }}>
                                                <option value="">-- Choisir --</option>
                                                @foreach ($options as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                            @break

                                        @case('checkbox')
                                            @foreach ($options as $opt)
                                                <div class="form-check">
                                                    <input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}" class="form-check-input" id="chk_{{ $champ->id }}_{{ $loop->index }}">
                                                    <label class="form-check-label" for="chk_{{ $champ->id }}_{{ $loop->index }}">{{ $opt }}</label>
                                                </div>
                                            @endforeach
                                            @break

                                        @case('file')
                                            <input type="file" name="{{ $name }}" class="form-control" {{ $required }}>
                                            @break

                                        @default
                                            <input type="text" name="{{ $name }}" class="form-control" {{ $required }}>
                                    @endswitch

                                    @error("champs.{$champ->id}")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <!-- Boutons -->
                            <div class="text-end">
                                <button type="button" id="confirm-submit" class="btn btn-success">Envoyer</button>
                                <a href="{{ route('tuteur.formulaires.affichage') }}" class="btn btn-light ms-2">Annuler</a>
                            </div>
                        </form>
                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end card-body -->
        </div> <!-- end container-fluid -->
    </div> <!-- end content -->
</div> <!-- end container -->
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Soumission avec confirmation
    document.getElementById('confirm-submit').addEventListener('click', function () {
        Swal.fire({
            title: 'Confirmer l’envoi',
            text: 'Êtes-vous sûr de vouloir soumettre ce formulaire ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, envoyer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formulaire-evaluation').submit();
            }
        });
    });

    // Alertes après redirection
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            confirmButtonColor: '#198754'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "{{ session('error') }}",
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
@endpush

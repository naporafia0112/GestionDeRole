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
                                            {{ $stage->candidature->candidat->nom ?? 'Nom inconnu' }} {{ $stage->candidature->candidat->prenoms ?? '' }} - {{ $stage->candidature->offre->titre ?? 'Offre inconnue' }}
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
                                    <label for="champ_{{ $champ->id }}" class="form-label">
                                        {{ $champ->label }}
                                        @if($champ->requis)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                    @switch($champ->type)
                                        @case('text')
                                            <input type="text" class="form-control @error('champs.'.$champ->id) is-invalid @enderror" id="champ_{{ $champ->id }}" name="champs[{{ $champ->id }}]" value="{{ old('champs.'.$champ->id) }}" @if($champ->requis) required @endif>
                                            @break

                                        @case('textarea')
                                            <textarea class="form-control @error('champs.'.$champ->id) is-invalid @enderror" id="champ_{{ $champ->id }}" name="champs[{{ $champ->id }}]" rows="4" @if($champ->requis) required @endif>{{ old('champs.'.$champ->id) }}</textarea>
                                            @break

                                        @case('number')
                                            <input type="number" class="form-control @error('champs.'.$champ->id) is-invalid @enderror" id="champ_{{ $champ->id }}" name="champs[{{ $champ->id }}]" value="{{ old('champs.'.$champ->id) }}" @if($champ->requis) required @endif>
                                            @break

                                        @case('date')
                                            <input type="date" class="form-control @error('champs.'.$champ->id) is-invalid @enderror" id="champ_{{ $champ->id }}" name="champs[{{ $champ->id }}]" value="{{ old('champs.'.$champ->id) }}" @if($champ->requis) required @endif>
                                            @break

                                        @case('select')
                                            <select class="form-select @error('champs.'.$champ->id) is-invalid @enderror" id="champ_{{ $champ->id }}" name="champs[{{ $champ->id }}]" @if($champ->requis) required @endif>
                                                <option value="">-- Choisissez --</option>
                                                @foreach($champ->options ?? [] as $option)
                                                    <option value="{{ $option }}" {{ old('champs.'.$champ->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @break

                                        @case('checkbox')
                                            <div class="form-check">
                                                <input class="form-check-input @error('champs.'.$champ->id) is-invalid @enderror" type="checkbox" id="champ_{{ $champ->id }}" name="champs[{{ $champ->id }}]" value="1" {{ old('champs.'.$champ->id) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="champ_{{ $champ->id }}">
                                                    {{ $champ->label }}
                                                </label>
                                            </div>
                                            @break

                                        @default
                                            <input type="text" class="form-control @error('champs.'.$champ->id) is-invalid @enderror" id="champ_{{ $champ->id }}" name="champs[{{ $champ->id }}]" value="{{ old('champs.'.$champ->id) }}">
                                    @endswitch

                                    @error('champs.'.$champ->id)
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
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

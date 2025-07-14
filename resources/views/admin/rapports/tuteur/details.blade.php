@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.tuteur') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('tuteur.formulaires.affichage') }}">Rapprots</a></li>
                                <li class="breadcrumb-item"><a href="">Formulaire d'évaluation</a></li>
                            </ol>
                        </div>
                    </div>
                        <h4 class="page-title">{{ $formulaire->titre }}</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="col-auto">
                                <a href="{{ route('tuteur.formulaires.affichage') }}" class="btn btn-sm btn-link">
                                    <i class="mdi mdi-keyboard-backspace"></i> Retour
                                </a>
                            </div>
                            {{-- Messages d'erreur globaux --}}
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

                            <form action="{{ route('tuteur.formulaires.store', $formulaire) }}" method="POST">
                                @csrf

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
                                                    @if($champ->options)
                                                        @foreach($champ->options as $option)
                                                            <option value="{{ $option }}" {{ old('champs.'.$champ->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                        @endforeach
                                                    @endif
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
                                <div class="text-end">
                                     <button type="submit" class="btn btn-success">Envoyer</button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container-fluid -->
    </div> <!-- end content -->
</div> <!-- end container -->
@endsection

@push('scripts')
<script>
    // Si tu veux ajouter du JS personnalisé ou des plugins ici (ex: datepickers, éditeur texte)
    $(document).ready(function(){
        // Exemple: initialiser un datepicker, ou autre
    });
</script>
@endpush

@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                    <li class="breadcrumb-item"><a href={{ route('entretiens.calendrier') }}>Calendrier</a></li>
                                    <li class="breadcrumb-item"><a href={{ route('entretiens.show-json',$entretien->id) }}>Details de l'entretiens</a></li>
                                </ol>
                            </div>
                            <h4 class="page-title">Modification de l'Entretien</h4>
                        </div>
                    </div>
                </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id=entretienForm action="{{ route('entretiens.update', $entretien->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', \Carbon\Carbon::parse($entretien->date)->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="heure" class="form-label">Heure</label>
                                    <input type="time" name="heure" id="heure" class="form-control" value="{{ old('heure', $entretien->heure) }}" >
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="lieu" class="form-label">Lieu</label>
                                    <input type="text" name="lieu" id="lieu" class="form-control" value="{{ old('lieu', $entretien->lieu) }}" >
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" id="type" class="selectize-select" >
                                        @foreach(App\Models\Entretien::TYPES as $value => $label)
                                            <option value="{{ $value }}" {{ old('type', $entretien->type) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="id_candidat" class="form-label">Candidat</label>
                                    <select class="form-control" disabled>
                                        @foreach ($candidats as $candidat)
                                            <option value="{{ $candidat->id }}" {{ $entretien->id_candidat == $candidat->id ? 'selected' : '' }}>
                                                {{ $candidat->nom }} {{ $candidat->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- champ hidden pour envoyer la vraie valeur -->
                                    <input type="hidden" name="id_candidat" value="{{ $entretien->id_candidat }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="id_offre" class="form-label">Offre</label>
                                    <select class="form-control" disabled>
                                        @foreach ($offres as $offre)
                                            <option value="{{ $offre->id }}" {{ $entretien->id_offre == $offre->id ? 'selected' : '' }}>
                                                {{ $offre->titre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- champ hidden pour envoyer la vraie valeur -->
                                    <input type="hidden" name="id_offre" value="{{ $entretien->id_offre }}">
                                </div>
                            </div>
                        </div>

                         <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut</label>
                                    <select name="statut" id="statut" class="selectize-select">
                                        <option value="">Sélectionner un statut</option>
                                        @foreach($statutsFiltres as $value => $label)
                                            <option value="{{ $value }}" {{ old('statut', $entretien->statut) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="commentaire" class="form-label">Commentaire</label>
                                    <textarea name="commentaire" id="commentaire" class="form-control" rows="4">{{ old('commentaire') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                                <a href="{{ route('entretiens.calendrier') }}" class="btn btn-ligth">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Selectize elements
        $('.selectize-select').selectize({
            create: false,
            sortField: 'text'
        });
        $('#entretienForm').on('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmation',
                text: "Souhaitez-vous Modifier cet entretien ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, Modifier',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush

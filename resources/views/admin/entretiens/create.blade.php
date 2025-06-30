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
                                </ol>
                            </div>
                            <h4 class="page-title">Formulaire de planification d'entretiens</h4>
                        </div>
                    </div>
                </div>                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="entretienForm" method="POST" action="{{ route('entretiens.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}">
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="heure" class="form-label">Heure</label>
                                    <input type="time" name="heure" id="heure" class="form-control" value="{{ old('heure') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="lieu" class="form-label">Lieu</label>
                                    <input type="text" name="lieu" id="lieu" class="form-control" value="{{ old('lieu') }}">
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" id="type" class="selectize-select" >
                                        <option value="">Sélectionner un type</option>
                                        @foreach(App\Models\Entretien::TYPES as $value => $label)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
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
                                    <label for="statut" class="form-label">Statut</label>
                                    <select name="statut" id="statut" class="selectize-select">
                                        <option value="">Sélectionner un statut</option>
                                        @foreach(App\Models\Entretien::STATUTS as $value => $label)
                                            <option value="{{ $value }}" {{ old('statut') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="commentaire" class="form-label">Commentaire</label>
                                    <textarea name="commentaire" id="commentaire" class="form-control">{{ old('commentaire') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="id_candidat" class="form-label">Candidat</label>
                                    <select name="id_candidat" id="id_candidat" class="selectize-select" >
                                        <option value="">Sélectionner un candidat</option>
                                        @foreach($candidats as $candidat)
                                            <option value="{{ $candidat->id }}" {{ old('id_candidat') == $candidat->id ? 'selected' : '' }}>
                                                {{ $candidat->nom }} {{ $candidat->prenoms }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="id_offre" class="form-label">Offre</label>
                                    <select name="id_offre" id="id_offre" class="selectize-select" >
                                        <option value="">Sélectionner une offre</option>
                                        @foreach($offres as $offre)
                                            <option value="{{ $offre->id }}" {{ old('id_offre') == $offre->id ? 'selected' : '' }}>
                                                {{ $offre->titre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Créer l'entretien</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize Selectize elements
        $('.selectize-select').selectize({
            create: false,
            sortField: 'text'
        });

        // SweetAlert confirmation
        document.getElementById('entretienForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirmation',
                text: "Souhaitez-vous créer cet entretien ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, créer',
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
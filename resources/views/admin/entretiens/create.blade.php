@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('entretiens.calendrier') }}">Calendrier</a></li>
                                <li class="breadcrumb-item"><a href="">Planifier un entretiens</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">Formulaire de planification d'entretiens</h4>
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
                        <span class="text-success">La date doit être une date ultérieure de 1h á la date actuel !</span>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $date ?? '') }}">                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="heure" class="form-label">Heure<span class="text-danger">*</span></label>
                                    <input type="time" name="heure" id="heure" class="form-control" value="{{ old('heure',$heure??'') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="lieu" class="form-label">Lieu<span class="text-danger">*</span></label>
                                    <input type="text" name="lieu" id="lieu" class="form-control" value="{{ old('lieu') }}" placeholder="Ex: Lomé">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type<span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="selectize-select">
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
                                    <label for="id_candidat" class="form-label">Candidat</label>
                                    <input type="text" class="form-control" disabled value="{{ $candidat->nom }} {{ $candidat->prenoms }}">
                                    <input type="hidden" name="id_candidat" value="{{ $candidat->id }}">
                                    <input type="hidden" name="id_candidat" value="{{ $id_candidat }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="id_offre" class="form-label">Offre</label>

                                    @if(isset($id_offre) && $id_offre)
                                        <select name="id_offre_display" class="form-control" disabled>
                                            @foreach($offres as $offre)
                                                @if($id_offre == $offre->id)
                                                    <option selected>{{ $offre->titre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="id_offre" value="{{ $id_offre }}">
                                    @else
                                        <input type="text" class="form-control" disabled value="Candidature spontanée - pas d'offre associée">
                                        <input type="hidden" name="id_offre" value="">
                                    @endif
                                </div>
                            </div>

                        </div>

                        {{-- Champ commentaire pleine largeur en bas --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="commentaire" class="form-label">Commentaire</label>
                                    <textarea name="commentaire" id="commentaire" class="form-control" rows="4">{{ old('commentaire') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Créer l'entretien</button>
                            <a href="{{ url()->previous() }}" class="btn btn-light">Annuler</a>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('.selectize-select').selectize();

        $('#entretienForm').on('submit', function (e) {
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

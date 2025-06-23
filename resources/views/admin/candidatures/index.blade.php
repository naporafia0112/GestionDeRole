@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href={{ route('offres.index') }}>Liste des offres</a></li>
                                <li class="breadcrumb-item"><a href=#>Liste des candidatures</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">Liste des offres</h4>
                    </div>
                </div>
            </div>

            <!-- Retour -->
            <div class="mb-3">
               <a href="{{ route('offres.index') }}" class="btn btn-sm btn-link">
                    <i class="mdi mdi-keyboard-backspace"></i> Retour
                </a>

            </div>
            <div class="mb-3 d-flex justify-content-end">
                <button id="btn-analyser-tout" class="btn btn-outline-dark">
                    <i class="mdi mdi-brain"></i> Analyser toutes les candidatures
                </button>
            </div>


            <!-- Candidatures -->
            @if($offre->candidatures->isEmpty())
                <div class="alert alert-info">
                    Aucune candidature enregistrée pour cette offre.
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped">
                                <thead>
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Candidat</th>
                                        <th>Statut</th>
                                        <th>Date de Soumission</th>
                                        <th><i class="mdi mdi-star"></i> IA</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($offre->candidatures as $candidature)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</td>
                                           <td>
                                                @if($candidature->statut === 'en_cours')
                                                    <span class="badge bg-warning text-dark">En cours de traitement</span>
                                                @elseif($candidature->statut === 'retenu')
                                                    <span class="badge bg-success">Retenu</span>
                                                @elseif($candidature->statut === 'rejete')
                                                    <span class="badge bg-danger">Rejeté</span>
                                                @else
                                                    <span class="badge bg-secondary">Inconnu</span>
                                                @endif
                                            </td>

                                            <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div id="atouts-{{ $candidature->id }}">
                                                    <!-- Résultat IA ici -->
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-sm me-1 btn-info" title="Voir">
                                                        <i class="fe-eye"></i>
                                                    </a>
                                                    <a href="" class="btn btn-sm  me-1 btn-outline-primary" title="Planifier entretien">
                                                        <i class="mdi mdi-calendar-clock"></i>
                                                    </a>
                                                    @if(!in_array($candidature->statut, ['rejete', 'retenu']))
                                                    <form action="{{ route('candidatures.reject', $candidature->id) }}" id="rejeter-candidature-{{ $candidature->id }}" method="POST" onsubmit="return confirm('Confirmer le rejet de cette candidature ?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button" class="btn btn-sm  me-1 btn-outline-danger" onclick="confirmRejet({{ $candidature->id}})" title="Rejeter">
                                                            <i class="mdi mdi-close-circle-outline"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                    <!-- Bouton Analyser avec IA -->
                                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-1" onclick="analyserIA({{ $candidature->id }})" title="Analyser avec IA">
                                                        <i class="mdi mdi-brain"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            @endif
        </div>
    </div>
</div>
<script>
    function confirmRejet(id) {
        Swal.fire({
            title: 'Rejeter cette candidature ?',
            text: "Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, Rejeter',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('rejeter-candidature-' + id).submit();
            }
        });
    }
</script>
<script>
    function analyserIA(candidatureId) {
        fetch(`/candidatures/${candidatureId}/analyser-ia`)
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: 'Analyse IA du profil',
                    html: `
                        <p><strong>Score de pertinence : </strong> ${data.score} / 100</p>
                        <div>
                            ${data.badges.map(b => `<span class="badge bg-info text-dark me-1 mb-1">${b}</span>`).join('')}
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Fermer'
                });
            })
            .catch(error => {
                Swal.fire('Erreur', 'Impossible d\'analyser cette candidature.', 'error');
                console.error(error);
            });
    }
</script>
<script>
    const candidatures = @json($offre->candidatures);

    document.getElementById('btn-analyser-tout').addEventListener('click', () => {
        Swal.fire({
            title: 'Analyse IA en cours...',
            text: 'Patientez quelques secondes',
            timer: 2500,
            showConfirmButton: false,
            allowOutsideClick: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Simuler une analyse IA
        setTimeout(() => {
            const resultats = candidatures.map(c => {
                const score = Math.floor(Math.random() * 100) + 1;
                const atouts = [];

                if (c.cv_fichier && Math.random() > 0.5) atouts.push("Expérience solide");
                if (Math.random() > 0.7) atouts.push("Sens de l’équipe");
                if (Math.random() > 0.5) atouts.push("Formation pertinente");
                if (Math.random() > 0.8) atouts.push("Langues étrangères");

                return {
                    id: c.id,
                    score,
                    atouts
                };
            });

            // Trier les résultats par score descendant
            resultats.sort((a, b) => b.score - a.score);

            // Injecter les résultats dans la page
            resultats.forEach(r => {
                const div = document.getElementById('atouts-' + r.id);
                if (div) {
                    div.innerHTML = `
                        <span class="badge bg-dark me-1">Score IA : ${r.score}%</span>
                        ${r.atouts.map(a => `<span class="badge bg-info text-dark me-1">${a}</span>`).join('')}
                    `;
                }
            });

            Swal.fire({
                icon: 'success',
                title: 'Analyse terminée',
                text: 'Les candidatures ont été évaluées.',
                timer: 2500
            });
        }, 2000);
    });
</script>

@endsection

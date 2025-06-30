<div class="table-responsive">
    <table class="table table-hover table-striped mb-0">
        <thead class="table-light">
            <tr>
                <th>Type</th>
                <th>Date</th>
                <th>Candidat</th>
                <th class="text-center">Statut</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($entretiens as $entretien)
                <tr>
                    <td>{{ $entretien->type ?? 'Sans titre' }}</td>
                    <td>{{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</td>
                    <td>{{ $entretien->candidat->nom }} {{ $entretien->candidat->prenoms }}</td>
                    <td class="text-center">
                        @switch($entretien->statut)
                            @case('prevu')
                                <span class="badge bg-info">Prévu</span>
                                @break
                            @case('en_cours')
                                <span class="badge bg-warning text-dark">En cours</span>
                                @break
                            @case('effectuee')
                                <span class="badge bg-primary">Effectuée</span>
                                @break
                            @case('termine')
                                <span class="badge bg-success">Terminé</span>
                                @break
                            @case('annule')
                                <span class="badge bg-danger">Annulé</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $entretien->statut }}</span>
                        @endswitch
                    </td>
                    <td class="text-center">
                        <a href="{{ route('entretiens.show', $entretien->id) }}" class="btn btn-sm btn-outline-primary" title="Voir détails">
                            <i class="mdi mdi-eye"></i>
                        </a>
                        @if ($entretien->statut == 'prevu')
                            <form action="{{ route('entretiens.annuler', $entretien->id) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Confirmer l\'annulation de cet entretien ?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Annuler l'entretien">
                                    <i class="mdi mdi-cancel"></i>
                                </button>
                            </form>
                        @elseif($entretien->statut !== 'annule')
                            <a href="{{ route('entretiens.edit', $entretien->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="mdi mdi-square-edit-outline"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Aucun entretien trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

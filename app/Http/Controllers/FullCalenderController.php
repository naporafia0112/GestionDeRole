<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entretien;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Entretien::whereDate('date_debut', '>=', $request->start)
                            ->whereDate('date_fin', '<=', $request->end)
                            ->get(['id', 'type as title', 'date_debut as start', 'date_fin as end']);
            return response()->json($data);
        }
        $data = Entretien::whereDate('date_debut', '>=', $request->start)
                 ->whereDate('date_fin', '<=', $request->end)
                 ->get(['id', 'type as title', 'date_debut as start', 'date_fin as end']);

        return view('admin.entretiens.calendrier'); // ta vue calendrier
    }

    public function action(Request $request)
    {
        if ($request->ajax()) {
            if ($request->type == 'add') {
                $entretien = Entretien::create([
                    'type'       => $request->title,    // ou un autre champ si tu préfères
                    'date_debut' => $request->start,
                    'date_fin'   => $request->end,
                    'lieu'       => $request->lieu ?? 'Lieu non défini', // optionnel si tu veux
                    'heure'      => $request->heure ?? null,
                    'statut'     => $request->statut ?? 'prévu',
                    'commentaire'=> $request->commentaire ?? null,
                    'id_candidat'=> $request->id_candidat ?? null,
                    'id_offre'   => $request->id_offre ?? null,
                ]);

                return response()->json($entretien);
            }

            if ($request->type == 'update') {
                $entretien = Entretien::find($request->id);

                if ($entretien) {
                    $entretien->update([
                        'type'       => $request->title,
                        'date_debut' => $request->start,
                        'date_fin'   => $request->end,
                        'lieu'       => $request->lieu ?? $entretien->lieu,
                        'heure'      => $request->heure ?? $entretien->heure,
                        'statut'     => $request->statut ?? $entretien->statut,
                        'commentaire'=> $request->commentaire ?? $entretien->commentaire,
                        'id_candidat'=> $request->id_candidat ?? $entretien->id_candidat,
                        'id_offre'   => $request->id_offre ?? $entretien->id_offre,
                    ]);
                }

                return response()->json($entretien);
            }

            if ($request->type == 'delete') {
                $entretien = Entretien::find($request->id);

                if ($entretien) {
                    $entretien->delete();
                }

                return response()->json(['success' => true]);
            }
        }
    }
}

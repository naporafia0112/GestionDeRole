<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Candidat;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CandidatureController extends Controller
{
    /* -----------------------------------------------------------------
     |  afficher le formulaire de candidature
     |------------------------------------------------------------------*/
    public function create(int $offreId)
    {
        $offre = Offre::findOrFail($offreId);
        return view('vitrine.postuler', compact('offre'));
    }

    /* -----------------------------------------------------------------
     | enregistrer la candidature
     |------------------------------------------------------------------*/
    public function store(Request $request, int $offreId)
    {
        $request->validate([
            // candidat
            'nom'        => 'required|string|max:255',
            'prenoms'    => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'telephone'  => 'nullable|string|max:30',
            'quartier'   => 'nullable|string|max:255',
            'ville'      => 'nullable|string|max:255',
            'type_depot' => 'required|in:stage professionnel,stage académique,stage de préembauche',

            // fichiers
            'cv_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'lm_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'lr_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        DB::transaction(function () use ($request, $offreId) {
            $candidat = Candidat::firstOrCreate(
                ['email' => $request->email],
                $request->only([
                    'nom', 'prenoms', 'telephone',
                    'quartier', 'ville', 'type_depot',
                ])
            );

            $cvPath = $request->file('cv_fichier')?->store('candidatures/cv', 'public');
            $lmPath = $request->file('lm_fichier')?->store('candidatures/lm', 'public');
            $lrPath = $request->file('lr_fichier')?->store('candidatures/lr', 'public');

            Candidature::create([
                'offre_id'       => $offreId,
                'candidat_id'    => $candidat->id,
                'statut'         => 'en cours de traitement',
                'cv_fichier'     => $cvPath,
                'lm_fichier'     => $lmPath,
                'lr_fichier'     => $lrPath,
            ]);
        });

        return redirect()
            ->route('vitrine.show', $offreId)
            ->with('success', 'Votre candidature a été envoyée avec succès.');
    }

     /* -----------------------------------------------------------------
     |  ADMIN
     |------------------------------------------------------------------*/
    /* -----------------------------------------------------------------
     |  liste des candidatures
     |------------------------------------------------------------------*/
    public function index()
    {
        $candidatures = Candidature::with(['candidat', 'offre'])
                           ->latest()
                           ->paginate(20);

        return view('candidatures.index', compact('candidatures'));
    }

    /* -----------------------------------------------------------------
     | détail d’une candidature
     |------------------------------------------------------------------*/
    public function show(int $id)
    {
        $candidature = Candidature::with(['candidat', 'offre'])->findOrFail($id);
        return view('candidatures.show', compact('candidature'));
    }

    /* -----------------------------------------------------------------
     | téléchargement sécurisé d’un fichier joint
     |------------------------------------------------------------------*/
    public function downloadFile(int $id, string $field)
{
    $candidature = Candidature::findOrFail($id);

    abort_unless(in_array($field, ['cv_fichier', 'lm_fichier', 'lr_fichier']), 404);

    $path = $candidature->$field;
    abort_if(!$path || !Storage::disk('public')->exists($path), 404);

    /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
    $disk = Storage::disk('public');

    return $disk->download($path);
}

}

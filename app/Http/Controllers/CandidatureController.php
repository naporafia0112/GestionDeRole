<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\Candidat;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Mail\CandidatureRecueMail;
use Illuminate\Support\Facades\Mail;
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
        ],[
            'nom.required' => 'Le nom est obligatoire.',
            'prenoms.required' => 'Les prénoms sont obligatoires.',
            'email.required' => 'L’adresse e-mail est obligatoire.',
            'email.email' => 'L’adresse e-mail n’est pas valide.',
            'type_depot.required' => 'Le type de dépôt est obligatoire.',
            'type_depot.in' => 'Le type de dépôt doit être l’un des suivants : stage professionnel, stage académique, stage de préembauche.',
            'cv_fichier.mimes' => 'Le fichier CV doit être au format PDF, DOC ou DOCX.',
            'lm_fichier.mimes' => 'Le fichier de lettre de motivation doit être au format PDF, DOC ou DOCX.',
            'lr_fichier.mimes' => 'Le fichier de lettre de recommandation doit être au format PDF, DOC ou DOCX.',
            'cv_fichier.max' => 'Le fichier CV ne doit pas dépasser 2 Mo.',
            'lm_fichier.max' => 'Le fichier de lettre de motivation ne doit pas dépasser 2 Mo.',
            'lr_fichier.max' => 'Le fichier de lettre de recommandation ne doit pas dépasser 2 Mo.',
        ]
    );

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
                'statut'         => 'en_cours',
                'cv_fichier'     => $cvPath,
                'lm_fichier'     => $lmPath,
                'lr_fichier'     => $lrPath,
            ]);
        });


        return redirect()
            ->route('vitrine.show', $offreId)
            ->with('success', 'Votre candidature a été envoyée avec succès.');
    }
    public function rejeter($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->statut = 'rejete';
        $candidature->save();

        return back()->with('success', 'La candidature a été rejetée avec succès.');
    }

    public function recherche(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string',
        ]);

        $candidature = Candidature::where('uuid', $request->uuid)
                        ->with('candidat')
                        ->first();

        $offres = Offre::all();

        $message = null;
        if (!$candidature) {
            $message = "Aucune candidature trouvée avec cet UUID.";
        }

        return view('vitrine.index', compact('candidature', 'offres', 'message'));
    }

    public function all()
    {
        $offres = Offre::with('localisation')->latest()->take(5)->get();
        return view('admin.candidatures.all', compact('candidatures'));
    }

    public function previewFile(int $id, string $field)
    {
        $candidature = Candidature::findOrFail($id);

        abort_unless(in_array($field, ['cv_fichier', 'lm_fichier', 'lr_fichier']), 404);

        $path = $candidature->$field;
        abort_if(!$path || !Storage::disk('public')->exists($path), 404);

        return response()->file(storage_path('app/public/' . $path));
    }


     /* -----------------------------------------------------------------
     |  ADMIN
     |------------------------------------------------------------------*/
    /* -----------------------------------------------------------------
     |  liste des candidatures
     |------------------------------------------------------------------*/
    public function index($offreId)
    {
        $offre = Offre::with(['candidatures' => function ($query) {
        $query->where('statut', '!=', 'rejete')->with('candidat');
        }])->findOrFail($offreId);
        return view('admin.candidatures.index', compact('offre'));
    }


    public function analyserIA(int $id)
    {
        $candidature = Candidature::with('candidat')->findOrFail($id);

        // 💡 Simulation de l’analyse IA (mock, version rapide)
        $score = rand(50, 95);

        $badges = [];

        // Analyse simulée selon des mots-clés dans le nom/prénom (à remplacer plus tard par NLP réel)
        if (str_contains(strtolower($candidature->candidat->nom), 'docteur')) {
            $badges[] = '🎓 Profil académique avancé';
        }

        if ($candidature->cv_fichier) {
            $badges[] = '📄 CV fourni';
        }

        if ($candidature->lr_fichier) {
            $badges[] = '💬 Recommandation présente';
        }

        if ($candidature->candidat->type_depot === 'stage professionnel') {
            $badges[] = '💼 Cherche un stage professionnel';
        }

        // Ajoute un badge général
        $badges[] = $score > 80 ? '✅ Très bon profil' : '⚠️ Profil à vérifier';

        return response()->json([
            'score' => $score,
            'badges' => $badges,
        ]);
    }

    /* -----------------------------------------------------------------
     | détail d’une candidature
     |------------------------------------------------------------------*/
    public function show(int $id)
    {
        $candidature = Candidature::with(['candidat', 'offre'])->findOrFail($id);
        return view('admin.candidatures.show', compact('candidature'));
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

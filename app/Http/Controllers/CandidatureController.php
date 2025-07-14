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
use App\Services\GeminiService;
use App\Helpers\Helpers;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Mail\CandidatureConfirmationMail;


class CandidatureController extends Controller
{


    public function analyze($id)
    {
        try {
            // On récupère la candidature avec l'offre associée
            $candidature = Candidature::with('offre')->findOrFail($id);

            // Récupération du chemin du fichier CV
            $cvFile = $candidature->cv_fichier;
            if (!$cvFile) {
                Log::error("Aucun fichier CV dans la candidature ID $id");
                return response()->json(['score' => 0, 'commentaire' => 'Aucun fichier CV trouvé.'], 404);
            }

            $cvPath = storage_path('app/public/' . $cvFile);

            if (!file_exists($cvPath)) {
                Log::error("Fichier CV introuvable : $cvPath");
                return response()->json(['score' => 0, 'commentaire' => 'CV introuvable.'], 404);
            }

            $parser = new Parser();
            $cvText = $parser->parseFile($cvPath)->getText();

            $jobDescription = $candidature->offre->description;

            $prompt = <<<EOT
                Tu es un recruteur. Voici une offre d'emploi :

                {$jobDescription}

                Et voici un CV extrait :

                {$cvText}

                Peux-tu analyser ce profil et lui donner une note sur 10 pour un poste de développeur web junior ?

                EOT;

            // Appel à l'API IA
            $response = Http::timeout(120)->post(config('ollama.url') . '/api/generate', [
                'model' => config('ollama.model'),
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if (!$response->successful()) {
                Log::error('Erreur API Ollama : ' . $response->body());
                return response()->json([
                    'score' => 0,
                    'commentaire' => 'Erreur lors de la communication avec l’IA.'
                ], 500);
            }

            $responseText = trim($response->json()['response'] ?? '');

            // Correction si la réponse est presque JSON mais mal formée
            if (!Str::endsWith($responseText, '}')) {
                $responseText .= '}';
            }

            $jsonResponse = json_decode($responseText, true);

            if ($jsonResponse === null) {
                Log::error('Erreur JSON décodage : ' . $responseText);
                return response()->json([
                    'score' => 0,
                    'commentaire' => 'Réponse IA JSON invalide.'
                ], 500);
            }

            // Sauvegarde du score et du commentaire dans la base
            $candidature->score = $jsonResponse['score'] ?? 0;
            $candidature->commentaire = $jsonResponse['commentaire'] ?? 'Analyse incomplète.';
            $candidature->save();

            return response()->json([
                'score' => $candidature->score,
                'commentaire' => $candidature->commentaire,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur analyse candidature ID ' . $id . ' : ' . $e->getMessage());
            return response()->json([
                'score' => 0,
                'commentaire' => 'Erreur serveur lors de l’analyse.'
            ], 500);
        }
    }

public function preselectionner($offreId)
{
    try {
        $offre = Offre::with('candidatures.candidat')->findOrFail($offreId);

        $parser = new Parser();
        $cvs = [];

        foreach ($offre->candidatures as $candidature) {
            $cvPath = storage_path('app/public/' . $candidature->cv_fichier);
            if (!file_exists($cvPath)) {
                Log::warning("CV manquant pour la candidature ID {$candidature->id}");
                continue;
            }

            try {
                $text = $parser->parseFile($cvPath)->getText();
                // Inclure l'ID réel dans le prompt
                $cvs[] = "---CV ID {$candidature->id}---\n" . $text;
            } catch (\Exception $e) {
                Log::error("Erreur parsing CV ID {$candidature->id} : " . $e->getMessage());
            }
        }

        if (empty($cvs)) {
            return response()->json(['message' => 'Aucun CV valide à analyser (PDFs manquants ou illisibles).'], 422);
        }

        $cvContent = implode("\n", $cvs);

        $prompt = <<<EOT
Tu es un recruteur.

Voici une offre d'emploi :

Titre : {$offre->titre}

Description :
{$offre->description}

Tâche :
1. Analyse les CV suivants.
2. Donne une note sur 100 à chacun.
3. Classe-les.
4. Retiens les 20 meilleurs en "selectionnes", les autres en "rejetes".

Voici maintenant une liste de CV :

{$cvContent}

Peux-tu faire une pré-sélection des CV et attribuer une note sur 100 à chaque profil avec un commentaire ?
Réponds uniquement en JSON :
{
  "selectionnes": [
    { "id": 123, "score": 92, "commentaire": "..." }
  ],
  "rejetes": [
    { "id": 456, "score": 45, "commentaire": "..." }
  ]
}
EOT;

        $response = Http::timeout(120)->post(config('ollama.url') . '/api/generate', [
            'model' => config('ollama.model'),
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if (!$response->successful()) {
            Log::error("Erreur lors de la requête à l’IA : " . $response->body());
            return response()->json(['message' => 'Erreur lors de la communication avec l’IA.'], 500);
        }

        $reponseBrute = $response->json()['response'] ?? '';

        try {
            $json = json_decode($reponseBrute, true);
            if (!is_array($json)) {
                throw new \Exception("JSON invalide");
            }
        } catch (\Throwable $e) {
            Log::error("Erreur de décodage JSON : " . $reponseBrute);
            return response()->json(['message' => 'Réponse IA non exploitable.'], 500);
        }

        foreach (['selectionnes' => 'retenu', 'rejetes' => 'rejete'] as $groupe => $statut) {
            foreach ($json[$groupe] ?? [] as $item) {
                $id = $item['id'] ?? null;

                if ($id && $offre->candidatures->contains('id', $id)) {
                    $candidature = $offre->candidatures->firstWhere('id', $id);
                    $candidature->score = $item['score'] ?? 0;
                    $candidature->commentaire = $item['commentaire'] ?? '';
                    $candidature->statut = $statut;
                    $candidature->save();
                }
            }
        }

        return response()->json(['message' => 'Préselection terminée avec succès.']);
    } catch (\Exception $e) {
        Log::critical("Échec de la présélection : " . $e->getMessage());
        return response()->json(['message' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
    }
}

    /**
     * Liste des candidatures liées à une offre.
     */
    public function index($offreId)
    {
        // 1. Récupération de l'offre avec ses candidatures et leurs entretiens (filtrés par offre)
        $offre = Offre::with(['candidatures' => function($query) use ($offreId) {
            $query->with(['entretien' => function($q) use ($offreId) {
                $q->where('id_offre', $offreId); // Assure qu'on filtre par l'offre actuelle
            }, 'candidat']); // Ajout de 'candidat' pour les infos du candidat
        }])->findOrFail($offreId);

        // 2. Groupement des candidatures par statut (pour affichage tabulé, par exemple)
        $candidaturesParStatut = $offre->candidatures->groupBy('statut');

        // 3. Récupération des candidatures "retenues" dont l'entretien est "effectuee" pour cette offre
       $retenuesEffectuees = Candidature::where('statut', 'retenu')
        ->where('offre_id', $offreId)
        ->whereHas('entretien', function ($q) use ($offreId) {
            $q->where('statut', 'effectuee')
            ->where('id_offre', $offreId);
        })
        ->with(['entretien', 'candidat', 'offre'])
        ->latest()
        ->paginate(10);


        return view('admin.candidatures.index', compact('offre', 'candidaturesParStatut', 'retenuesEffectuees'));
    }



    /**
     * Rejeter une candidature
     */
    public function rejeter($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->statut = 'rejete';
        $candidature->save();

        return back()->with('success', 'La candidature a été rejetée avec succès.');
    }

    public function retenir($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->statut = 'retenu';
        $candidature->save();

        return back()->with('success', 'La candidature a été retenue.');
    }

    public function valider($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->statut = 'valide';
        $candidature->save();

        return redirect()->route('stages.create', [
            'id_candidature' => $candidature->id,
            'id_offre' => $candidature->offre_id,
        ])->with('success', 'Candidature validée. Vous pouvez maintenant créer un stage.');
    }


    public function effectuee($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->statut = 'effectuee';
        $candidature->save();

        return back()->with('success', 'La session a été marquée comme effectuée.');
    }

   public function show($id)
    {
        $candidature = Candidature::with('candidat', 'offre', 'entretien')->findOrFail($id);
        $toutesCandidatures = Candidature::orderBy('id')->pluck('id')->toArray();
        $numero = array_search($candidature->id, $toutesCandidatures) + 1;

        $statut = $candidature->statut; // récupère le statut ici

        return view('admin.candidatures.show', compact('candidature', 'numero', 'statut'));
    }


    public function create(int $offreId)
    {
        $offre = Offre::findOrFail($offreId);
        return view('vitrine.postuler', compact('offre'));
    }

    public function store(Request $request, int $offreId)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:30',
            'quartier' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'type_depot' => 'required|in:stage professionnel,stage académique,stage de préembauche',
            'cv_fichier' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'lm_fichier' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'lr_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        DB::transaction(function () use ($request, $offreId) {
            $candidat = Candidat::firstOrCreate(
                ['email' => $request->email],
                $request->only(['nom', 'prenoms', 'telephone', 'quartier', 'ville', 'type_depot'])
            );

            $cvPath = $request->file('cv_fichier')?->store('candidatures/cv', 'public');
            $lmPath = $request->file('lm_fichier')?->store('candidatures/lm', 'public');
            $lrPath = $request->file('lr_fichier')?->store('candidatures/lr', 'public');

            $candidature = Candidature::create([
                'offre_id' => $offreId,
                'candidat_id' => $candidat->id,
                'statut' => 'en_cours',
                'cv_fichier' => $cvPath,
                'lm_fichier' => $lmPath,
                'lr_fichier' => $lrPath,
            ]);

            // Envoyer l'email de confirmation au candidat
            Mail::to($candidat->email)->send(new CandidatureConfirmationMail($candidature));
        });

        return redirect()->route('vitrine.show', $offreId)->with('success', 'Votre candidature a été envoyée avec succès. Un mail de confirmation vous a été envoyé.');
    }

    public function recherche(Request $request)
    {
        $request->validate(['uuid' => 'required|string']);
        $candidature = Candidature::where('uuid', $request->uuid)->with('candidat')->first();
        $offres = Offre::all();
        $message = !$candidature ? "Aucune candidature trouvée avec cet UUID." : null;

        return view('vitrine.recherche', compact('candidature', 'offres', 'message'));
    }

    public function all()
    {
        $candidatures = Candidature::with(['candidat', 'offre'])->latest()->paginate(10);
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

    public function downloadFile(int $id, string $field)
    {
        $candidature = Candidature::findOrFail($id);
        abort_unless(in_array($field, ['cv_fichier', 'lm_fichier', 'lr_fichier']), 404);
        $path = $candidature->$field;
        abort_if(!$path || !Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->download($path);
    }

    /*public function analyser(Request $request)
    {
        $criteres = $request->input('criteres', []);
        $prompt = $this->construirePrompt($criteres);
        $parser = new Parser();
        $fichiers = Storage::files('candidatures');
        $texte = "Voici une liste de CVs de candidats :\n\n";

        foreach ($fichiers as $fichier) {
            $nom = pathinfo($fichier, PATHINFO_FILENAME);
            $pdf = $parser->parseFile(storage_path('app/' . $fichier));
            $texte .= "Candidat : $nom\nCV :\n" . $pdf->getText() . "\n\n";
        }

        Log::info("Prompt IA : " . $prompt . "\n\n" . $texte);
        $analyse = Helpers::analyserCandidaturesAvecGemini($prompt . "\n\n" . $texte);

        return view('admin.candidatures.analyse_resultats', compact('analyse', 'prompt'));
    }

    private function construirePrompt(array $criteres)
    {
        $base = "Analyse les profils de ces candidats pour un poste et classe-les du plus au moins adapté.";
        $map = [
            'experience' => "l'expérience professionnelle",
            'recommandation' => "la réputation de l’entreprise ou personne qui recommande",
            'coherence' => "la cohérence entre le profil et le poste",
        ];
        if ($criteres) {
            $base .= " Critères : " . implode(', ', array_map(fn($c) => $map[$c] ?? $c, $criteres)) . ".";
        }
        return $base;
    }

    public function analyserIA(int $id)
    {
        $candidature = Candidature::with('candidat')->findOrFail($id);
        $score = rand(50, 95);
        $badges = [];

        if (str_contains(strtolower($candidature->candidat->nom), 'docteur')) {
            $badges[] = '🎓 Profil académique avancé';
        }
        if ($candidature->cv_fichier) {
            $badges[] = 'CV fourni';
        }
        if ($candidature->lr_fichier) {
            $badges[] = 'Recommandation présente';
        }
        if ($candidature->candidat->type_depot === 'stage professionnel') {
            $badges[] = 'Cherche un stage professionnel';
        }
        $badges[] = $score > 80 ? 'Très bon profil' : 'Profil à vérifier';

        return response()->json([
            'score' => $score,
            'badges' => $badges,
        ]);
    }*/
    // Afficher les candidatures retenues
    public function dossiersRetenus()
    {
        $candidatures = Candidature::with(['candidat', 'offre'])
            ->where('statut', 'retenu')
            ->latest()
            ->paginate(10);

        return view('admin.entretiens.retenus', ['candidatures' => $candidatures,'statut' => 'retenu',]);
    }

    // Afficher les candidatures validées
    public function dossiersValides()
    {
        $candidatures = Candidature::with(['candidat', 'offre'])
            ->where('statut', 'valide')
            ->latest()
            ->paginate(10);

        return view('admin.dossiers.dossiersvalide.valides', compact('candidatures'));
    }

}

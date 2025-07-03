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

            // Analyse du contenu du CV avec Smalot
            $parser = new Parser();
            $cvText = $parser->parseFile($cvPath)->getText();

            $jobDescription = $candidature->offre->description;

            // Création du prompt pour l'IA
            $prompt = <<<EOT
            Tu es un recruteur. Voici une offre d'emploi :

            {$jobDescription}

            Et voici un CV extrait :

            {$cvText}

            Analyse la pertinence de ce candidat pour cette offre.

            Réponds uniquement en JSON :
            {
            "score": 80,
            "commentaire": "Bon profil mais manque de DevOps"
            } si tu ne trouves aucune information pertinente, réponds en JSON :
            {
            "score": 0,
            "commentaire": "Aucune information pertinente trouvée."
            } et si tu ne trouves pas de CV, réponds en JSON :
            {
            "score": 0,
            "commentaire": "Aucun CV trouvé."
            }
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


    /**
     * Liste des candidatures liées à une offre.
     */
    public function index($offreId)
    {
        $offre = Offre::with([
            'candidatures.entretien',
            'candidatures.candidat'
        ])->findOrFail($offreId);
        return view('admin.candidatures.index', compact('offre'));
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

        return back()->with('success', 'La candidature a été validée.');
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
        $candidature = Candidature::with('candidat', 'offre')->findOrFail($id);
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

            Candidature::create([
                'offre_id' => $offreId,
                'candidat_id' => $candidat->id,
                'statut' => 'en_cours',
                'cv_fichier' => $cvPath,
                'lm_fichier' => $lmPath,
                'lr_fichier' => $lrPath,
            ]);
        });

        return redirect()->route('vitrine.show', $offreId)->with('success', 'Votre candidature a été envoyée avec succès.');
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

    public function analyser(Request $request)
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
    }
    // Afficher les candidatures retenues
    public function dossiersRetenus()
    {
        $candidatures = Candidature::with(['candidat', 'offre'])
            ->where('statut', 'retenu')
            ->latest()
            ->paginate(10);

        return view('admin.dossiers.dossiersretenu.retenus', ['candidatures' => $candidatures,'statut' => 'retenu',]);
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

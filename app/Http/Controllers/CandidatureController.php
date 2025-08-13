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
use App\Jobs\EnvoyerMailCandidature;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Mail\CandidatureConfirmationMail;
use App\Models\CandidatureSpontanee;
use App\Models\User;
use App\Notifications\NouvelleCandidatureNotification;

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
        // Récupérer l'offre avec ses candidatures
        $offre = Offre::with(['candidatures.candidat', 'candidatures' => function($query) {
            $query->where('statut', 'en_cours'); // Seulement les non traitées
        }])->findOrFail($offreId);

        if ($offre->candidatures->isEmpty()) {
            return response()->json([
                'message' => 'Aucune candidature en cours à analyser.'
            ], 422);
        }

        Log::info("Début de présélection pour l'offre {$offreId} - {$offre->candidatures->count()} candidatures");

        $parser = new Parser();
        $candidaturesData = [];
        $candidaturesValides = [];

        // Phase 1: Extraction et validation des CVs
        foreach ($offre->candidatures as $candidature) {
            $cvPath = storage_path('app/public/' . $candidature->cv_fichier);

            if (!file_exists($cvPath)) {
                Log::warning("CV manquant pour candidature ID {$candidature->id}: {$cvPath}");
                continue;
            }

            try {
                $text = $parser->parseFile($cvPath)->getText();

                // Nettoyer et valider le texte
                $text = trim(preg_replace('/\s+/', ' ', $text));

                if (strlen($text) < 100) { // CV trop court
                    Log::warning("CV ID {$candidature->id} trop court ({$candidature->candidat->nom})");
                    continue;
                }

                $candidaturesData[] = [
                    'id' => $candidature->id,
                    'nom' => $candidature->candidat->nom . ' ' . $candidature->candidat->prenoms,
                    'texte' => $text
                ];

                $candidaturesValides[] = $candidature;

                Log::info("CV ID {$candidature->id} extrait avec succès ({$candidature->candidat->nom})");

            } catch (\Exception $e) {
                Log::error("Erreur parsing CV ID {$candidature->id}: " . $e->getMessage());
                continue;
            }
        }

        if (empty($candidaturesData)) {
            return response()->json([
                'message' => 'Aucun CV valide trouvé. Vérifiez que les fichiers PDF sont présents et lisibles.'
            ], 422);
        }

        // Phase 2: Préparation du prompt optimisé
        $cvContent = '';
        foreach ($candidaturesData as $cv) {
            $cvContent .= "---CV ID {$cv['id']} - {$cv['nom']}---\n";
            $cvContent .= substr($cv['texte'], 0, 2000) . "\n\n"; // Limiter la taille
        }

        $prompt = $this->construirePrompt($offre, $cvContent, count($candidaturesData));

        Log::info("Envoi de la requête à Ollama - " . count($candidaturesData) . " CVs à analyser");

        // Phase 3: Appel à l'IA avec retry
        $response = $this->appellerOllamaAvecRetry($prompt, 3);

        if (!$response['success']) {
            return response()->json([
                'message' => $response['error']
            ], 500);
        }

        // Phase 4: Traitement de la réponse
        $resultats = $this->traiterReponseIA($response['data'], $candidaturesValides);

        if (!$resultats['success']) {
            return response()->json([
                'message' => $resultats['error']
            ], 500);
        }

        // Phase 5: Mise à jour en base de données
        $stats = $this->mettreAJourCandidatures($resultats['data'], $candidaturesValides);

        Log::info("Présélection terminée - Retenus: {$stats['retenus']}, Rejetés: {$stats['rejetes']}");

        return response()->json([
            'message' => "Présélection terminée avec succès. {$stats['retenus']} candidats retenus, {$stats['rejetes']} rejetés.",
            'stats' => $stats
        ]);

    } catch (\Exception $e) {
        Log::critical("Échec critique de la présélection offre {$offreId}: " . $e->getMessage());
        return response()->json([
            'message' => 'Une erreur critique est survenue. Veuillez contacter l\'administrateur.'
        ], 500);
    }
}

/**
 * Construit un prompt optimisé pour l'IA
 */
private function construirePrompt($offre, $cvContent, $nbCandidats)
{
    $nbAReterenir = min(20, max(3, ceil($nbCandidats * 0.3))); // 30% ou max 20

    return <<<EOT
Tu es un expert en recrutement. Analyse ces CVs pour cette offre d'emploi.

OFFRE D'EMPLOI:
Titre: {$offre->titre}
Description: {$offre->description}

CRITÈRES D'ÉVALUATION:
- Adéquation compétences/poste (40%)
- Expérience pertinente (30%)
- Formation appropriée (20%)
- Autres atouts (10%)

TÂCHE:
1. Analyse chaque CV individuellement
2. Attribue un score de 0 à 100 (entier)
3. Classe les candidats par score décroissant
4. Retiens uniquement les candidats ayant un score strictement supérieur à 80 dans "selectionnes"
5. Classe les autres comme "rejetes"
6. Écris un commentaire court (max 100 caractères) pour chaque candidat

CVS À ANALYSER:
{$cvContent}

Règles obligatoires :
- Ne jamais inclure dans "selectionnes" un candidat avec un score ≤ 80.
- Le champ "commentaire" doit toujours être une phrase complète.
- Le total des candidats dans "selectionnes" + "rejetes" doit égaler le nombre total d’entrées fournies.

RÉPONSE ATTENDUE:
TU DOIS ABSOLUMENT RÉPONDRE AVEC DU JSON VALIDE ET RIEN D'AUTRE.
LA RÉPONSE DOIT ÊTRE UN OBJET JSON CONTENANT DEUX CLÉS, "selectionnes" ET "rejetes", CHACUNE CONTENANT UN TABLEAU D'OBJETS AVEC LES CLÉS "id", "score", et "commentaire".
NE RENVOIE AUCUN TEXTE ADDITIONNEL, AUCUNE INTRODUCTION, AUCUNE EXPLICATION AVANT OU APRÈS LE JSON.
EOT;
}

/**
 * Appelle Ollama avec système de retry
 */
private function appellerOllamaAvecRetry($prompt, $maxRetries = 3)
{
    $tentative = 0;

    while ($tentative < $maxRetries) {
        $tentative++;

        try {
            $response = Http::timeout(300)->post(config('ollama.url') . '/api/generate', [
                'model' => config('ollama.model', 'llama3.1:8b'),
                'prompt' => $prompt,
                'stream' => false,
                'options' => [
                    'temperature' => 0.3, // Plus déterministe
                    'top_p' => 0.9,
                    'repeat_penalty' => 1.1
                ]
            ]);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['response'])) {
                    return [
                        'success' => true,
                        'data' => $body['response']
                    ];
                }
            }

            Log::warning("Tentative {$tentative} échouée - Code: " . $response->status());

        } catch (\Exception $e) {
            Log::error("Erreur tentative {$tentative}: " . $e->getMessage());
        }

        if ($tentative < $maxRetries) {
            sleep(2 * $tentative); // Délai progressif
        }
    }

    return [
        'success' => false,
        'error' => 'Impossible de communiquer avec l\'IA après ' . $maxRetries . ' tentatives.'
    ];
}

/**
 * Traite et valide la réponse de l'IA
 */
/**
 * Traite et valide la réponse de l'IA
 */
private function traiterReponseIA($reponseIA, $candidaturesValides)
{
    try {
        // Enregistrer la réponse brute pour le débogage
        Log::info("Réponse brute de l'IA reçue: " . $reponseIA);

        // --- 1. Extraire la partie JSON avec une approche plus robuste ---
        // Chercher le premier caractère '{' et le dernier '}' pour isoler le JSON
        $debut = strpos($reponseIA, '{');
        $fin = strrpos($reponseIA, '}');

        if ($debut !== false && $fin !== false && $fin > $debut) {
            $jsonString = substr($reponseIA, $debut, $fin - $debut + 1);
        } else {
            throw new \Exception('Aucun bloc JSON exploitable trouvé dans la réponse.');
        }

        // --- 2. Tenter de décoder le JSON nettoyé ---
        $json = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON invalide: ' . json_last_error_msg() . '. JSON brut: ' . $jsonString);
        }

        // --- 3. Validation de la structure JSON (clés principales) ---
        if (!isset($json['selectionnes']) || !isset($json['rejetes'])) {
            throw new \Exception('Structure JSON incorrecte - clés manquantes');
        }

        // --- 4. Validation et nettoyage des données internes ---
        $idsValides = collect($candidaturesValides)->pluck('id')->toArray();
        $idsRecus = [];
        $dataTraitee = ['selectionnes' => [], 'rejetes' => []];

        foreach (['selectionnes', 'rejetes'] as $groupe) {
            foreach ($json[$groupe] as $item) {
                // Vérifier si l'item a les clés requises
                if (!isset($item['id']) || !isset($item['score']) || !isset($item['commentaire'])) {
                    Log::warning("Item invalide dans {$groupe}: structure incomplète. Ignoré.");
                    continue;
                }

                $id = (int)$item['id'];

                // Vérifier si l'ID est bien dans notre liste de candidatures à traiter
                if (!in_array($id, $idsValides)) {
                    Log::warning("ID {$id} non valide pour cette offre dans {$groupe}. Ignoré.");
                    continue;
                }

                // Vérifier les doublons
                if (in_array($id, $idsRecus)) {
                    Log::warning("ID {$id} en double. Ignoré.");
                    continue;
                }

                // Si tout est bon, on ajoute l'ID à la liste des IDs traités et on nettoie les valeurs
                $idsRecus[] = $id;

                $score = (int)$item['score'];
                $commentaire = substr(trim($item['commentaire']), 0, 200);

                // Ajouter l'élément validé à notre tableau final
                $dataTraitee[$groupe][] = [
                    'id' => $id,
                    'score' => max(0, min(100, $score)), // S'assurer que le score est entre 0 et 100
                    'commentaire' => $commentaire
                ];
            }
        }

        Log::info("Validation réussie - IDs traités: " . implode(', ', $idsRecus));

        return [
            'success' => true,
            'data' => $dataTraitee // Retourner le tableau nettoyé
        ];

    } catch (\Exception $e) {
        // En cas d'échec, on log l'erreur avec un message explicite
        Log::error("Erreur traitement réponse IA: " . $e->getMessage());

        return [
            'success' => false,
            'error' => 'Réponse de l\'IA non exploitable: ' . $e->getMessage()
        ];
    }
}
/**
 * Met à jour les candidatures en base
 */
private function mettreAJourCandidatures($resultats, $candidaturesValides)
{
    $stats = ['retenus' => 0, 'rejetes' => 0, 'erreurs' => 0];

    // Convertir le tableau en une collection pour utiliser firstWhere()
    $candidaturesCollection = collect($candidaturesValides);

    DB::beginTransaction();

    try {
        foreach (['selectionnes' => 'retenu', 'rejetes' => 'rejete'] as $groupe => $statut) {
            foreach ($resultats[$groupe] as $item) {
                // Utiliser la collection pour la recherche
                $candidature = $candidaturesCollection->firstWhere('id', $item['id']);

                if ($candidature) {
                    $candidature->update([
                        'score' => (int)$item['score'],
                        'commentaire' => $item['commentaire'],
                        'statut' => $statut,
                        'date_traitement' => now()
                    ]);

                    $stats[$statut === 'retenu' ? 'retenus' : 'rejetes']++;

                    Log::info("Candidature ID {$candidature->id} mise à jour: {$statut}, score: {$item['score']}");
                } else {
                    $stats['erreurs']++;
                    Log::error("Candidature ID {$item['id']} introuvable pour mise à jour");
                }
            }
        }

        DB::commit();
        return $stats;

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Erreur mise à jour base: " . $e->getMessage());
        throw $e;
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
            'id_candidature' => $candidature->id
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

        try {
            // On initialise $candidature ici pour y avoir accès après la transaction
            $candidature = null;

            DB::transaction(function () use ($request, $offreId, &$candidature) {
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

                // Notifications internes (ça peut rester dans la transaction)
                $rhs = User::whereHas('roles', function ($q) {
                    $q->where('name', 'RH');
                })->get();

                foreach ($rhs as $rh) {
                    $rh->notify(new NouvelleCandidatureNotification($candidature));
                }
            });

            // Dispatch du job après la transaction
            EnvoyerMailCandidature::dispatch($candidature);
        }
        catch (\Exception $e) {
            Log::error('Erreur création candidature : ' . $e->getMessage());
            return back()->withErrors('Une erreur est survenue, veuillez réessayer.');
        }

        return redirect()->route('vitrine.show', $offreId)
            ->with('success', 'Votre candidature a été envoyée avec succès. Un mail de confirmation vous sera envoyé bientôt.');
    }


   public function recherche(Request $request)
    {
        $request->validate(['uuid' => 'required|string']);

        // Recherche d'une candidature classique
        $candidature = Candidature::with('candidat')->where('uuid', $request->uuid)->first();
        $type = 'classique';

        // Si pas trouvée, recherche une candidature spontanée
        if (!$candidature) {
            $candidature = CandidatureSpontanee::where('uuid', $request->uuid)->first();
            $type = $candidature ? 'spontanee' : null;
        }

        $offres = Offre::all(); // Si tu veux afficher la liste des offres dans la vue
        $message = !$candidature ? "Aucune candidature trouvée avec cet UUID." : null;

        return view('vitrine.recherche', compact('candidature', 'offres', 'message', 'type'));
    }
    public function all()
    {
        $candidatures = Candidature::with(['candidat', 'offre'])->latest()->paginate(10);
        return view('admin.candidatures.all', compact('candidatures'));
    }

    public function preview($id, $field)
    {
        $candidature = Candidature::findOrFail($id);
        $fichier = $candidature->$field;

        if (!$fichier || !Storage::disk('public')->exists($fichier)) {
            abort(404, 'Fichier introuvable');
        }

        return response()->file(storage_path('app/public/' . $fichier));
    }
    public function downloadFile(int $id, string $field)
    {
        $candidature = Candidature::findOrFail($id);
        abort_unless(in_array($field, ['cv_fichier', 'lm_fichier', 'lr_fichier']), 404);
        $path = $candidature->$field;
        abort_if(!$path || !Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->download($path);
    }

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

    public function renvoyerEmail(Request $request)
    {
        // Validation simple
        $request->validate([
            'email' => 'required|email',
        ]);

        // Recherche du candidat
        $candidat = Candidat::where('email', $request->email)->first();

        if (!$candidat) {
            return back()->withInput()->with('error', 'Aucun email correspondant trouvé.')
                        ->with('open_modal', true);
        }

        // Récupération de la dernière candidature
        $candidature = $candidat->candidatures()->latest()->first();

        if (!$candidature) {
            return back()->withInput()->with('error', 'Aucune candidature trouvée pour cet email.')
                        ->with('open_modal', true);
        }

        // Envoi de l'email
        Mail::to($candidat->email)->send(new CandidatureConfirmationMail($candidature));

        return back()->with('success', 'Email renvoyé avec succès.');
    }


}

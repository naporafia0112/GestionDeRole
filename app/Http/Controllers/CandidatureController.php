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

class CandidatureController extends Controller
{
    /**
     * Liste des candidatures liées à une offre.
     */
    public function index($offreId)
    {
        $offre = Offre::with(['candidatures' => function ($query) {
            $query->orderBy('created_at', 'desc')->with('candidat');
        }])->findOrFail($offreId);

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

        return view('admin.candidatures.show', compact('candidature', 'numero'));
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
            'telephone' => 'nullable|string|max:30',
            'quartier' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'type_depot' => 'required|in:stage professionnel,stage académique,stage de préembauche',
            'cv_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'lm_fichier' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
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
            $badges[] = '📄 CV fourni';
        }
        if ($candidature->lr_fichier) {
            $badges[] = '💬 Recommandation présente';
        }
        if ($candidature->candidat->type_depot === 'stage professionnel') {
            $badges[] = '💼 Cherche un stage professionnel';
        }
        $badges[] = $score > 80 ? 'Très bon profil' : 'Profil à vérifier';

        return response()->json([
            'score' => $score,
            'badges' => $badges,
        ]);
    }
}
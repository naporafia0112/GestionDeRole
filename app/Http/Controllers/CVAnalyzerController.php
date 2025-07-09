<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

class CVAnalyzerController extends Controller
{
    protected $ollamaUrl;
    protected $model;
    protected $timeout;

    public function __construct()
    {
        $this->ollamaUrl = config('ollama.url');
        $this->model = config('ollama.model');
        $this->timeout = config('ollama.timeout');
    }

    public function form()
    {
        return view('cv.form');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'cv_file' => 'required|file|mimes:pdf',
            'job_description' => 'required|string',
        ]);

        $file = $request->file('cv_file');

        // Vérification stricte PDF
        if ($file->getClientOriginalExtension() !== 'pdf') {
            return response()->json([
                'error' => 'Seuls les fichiers PDF sont pris en charge actuellement.'
            ], 422);
        }

        $jobDescription = $request->input('job_description');
        $cvText = $this->extractTextFromPDF($file);

        $prompt = <<<EOT
Tu es un recruteur. Voici une offre d'emploi :

{$jobDescription}

Et voici un CV extrait :

{$cvText}

Analyse la pertinence de ce candidat pour cette offre.

 Réponds uniquement en JSON avec :
- un champ "score" (sur 100)
- un champ "commentaire" avec une seule phrase très courte (moins de 20 mots).

Exemple :
{
  "score": 91,
  "commentaire": "Profil solide en PHP et Laravel, un peu faible en DevOps."
}
EOT;

        $response = Http::timeout($this->timeout)->post($this->ollamaUrl . '/api/generate', [
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => false,
        ]);

        $data = $response->json();

        // Décodage du champ 'response' (Ollama renvoie une string JSON)
        $parsed = json_decode($data['response'], true);

        return response()->json([
            'score' => $parsed['score'] ?? null,
            'commentaire' => $parsed['commentaire'] ?? 'Réponse mal formatée',
        ]);
    }

    private function extractTextFromPDF($file)
    {
        $parser = new Parser();
        return $parser->parseFile($file->getRealPath())->getText();
    }
}

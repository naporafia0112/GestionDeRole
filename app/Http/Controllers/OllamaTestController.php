<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OllamaTestController extends Controller
{
    protected $url;
    protected $model;
    protected $timeout;

    public function __construct()
    {
        $this->url = config('ollama.url');
        $this->model = config('ollama.model');
        $this->timeout = config('ollama.timeout');
    }

    public function index()
    {
        return view('ollama.test');
    }

    public function testConnection()
    {
        try {
            $response = Http::timeout($this->timeout)->get($this->url);
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listModels()
    {
        try {
            $response = Http::timeout($this->timeout)->get($this->url . '/api/tags');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   public function analyzeCV(Request $request)
    {
        $userPrompt = $request->input('prompt', 'Analyse ce CV');

        // Ici, on peut préciser qu’on veut une réponse courte
        $finalPrompt = "Analyse ce CV en une phrase très courte avec une note sur 10 :\n sans remarques" . $userPrompt;

        $response = Http::timeout($this->timeout)->post($this->url . '/api/generate', [
            'model' => $this->model,
            'prompt' => $finalPrompt,
            'stream' => false,
        ]);

        return response()->json($response->json());
    }


    public function testWithSampleCV()
    {
        $prompt = <<<EOT
Voici un extrait de CV :
Nom: Amina Koffi
Compétences : Laravel, PHP, MySQL, HTML, CSS, JS
Expérience : Développeuse web chez DevCorp (2021-2023)
Éducation : Licence Informatique

Peux-tu analyser ce profil et lui donner une note sur 10 pour un poste de développeur web junior ?
EOT;

        $response = Http::timeout($this->timeout)->post($this->url . '/api/generate', [
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => false,
        ]);

        return response()->json($response->json());
    }
    public function preselectionTest()
{
    $prompt = <<<EOT
Tu es un recruteur.

Voici une offre d'emploi :

Titre : Développeur Laravel

Description :
Nous recherchons un développeur Laravel junior motivé, à l'aise avec PHP, MySQL, et les technologies web.

Voici maintenant une liste de CV :

---CV 1---
Nom : Amina Koffi
Compétences : Laravel, PHP, MySQL, HTML, CSS
Expérience : 2 ans chez DevCorp
Formation : Licence informatique

---CV 2---
Nom : Junior Komlan
Compétences : Word, Excel, comptabilité
Expérience : Stagiaire en compta
Formation : BTS Gestion

---CV 3---
Nom : Salif Yao
Compétences : Laravel, React, API REST
Expérience : 3 ans freelance
Formation : Master informatique

Peux-tu :
1. Analyser les CV
2. Donner une note sur 100 à chacun
3. Renvoyer les 20 meilleurs en "selectionnes", les autres en "rejetes"
4. Réponds uniquement en JSON comme ci-dessous :
{
"selectionnes": [
  { "cv_id": 1, "score": 90, "commentaire": "Très bon profil." }
],
"rejetes": [
  { "cv_id": 2, "score": 30, "commentaire": "Profil hors sujet." }
]
}
EOT;

    try {
        $response = Http::timeout($this->timeout)->post($this->url . '/api/generate', [
            'model' => $this->model,
            'prompt' => $prompt,
            'stream' => false,
        ]);

        return response()->json($response->json());
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}

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
}

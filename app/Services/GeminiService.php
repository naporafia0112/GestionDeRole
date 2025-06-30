<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function classerCandidatures(array $pdfs, string $promptGlobal): string
    {
        $apiKey = config('services.gemini.api_key');
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=$apiKey";

        $texte = "Voici une liste de CVs de candidats :\n\n";

        foreach ($pdfs as $nom => $contenu) {
            $texte .= "Candidat : $nom\nCV :\n$contenu\n\n";
        }

        $prompt = $promptGlobal . "\n\n" . $texte . "\nClasse les candidats du plus prometteur au moins prometteur, avec un score et une justification.";

        $response = Http::post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
        ]);

        if ($response->successful()) {
            return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Aucune réponse générée.';
        }

        return 'Erreur : ' . $response->body();
    }
}

<?php

namespace App\Helpers;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use OpenAI;

class Helpers
{
   public static function analyserCandidaturesAvecGemini($texte)
    {
        try {
            Log::info("Prompt envoyé à Gemini : " . $texte);

            $apiKey = env('GEMINI_API_KEY');
            $url = 'https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=' . $apiKey;

            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $texte]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Erreur Gemini IA : ' . $response->body());
                return "Erreur de l'IA lors de l’analyse des candidatures.";
            }

            return $response->json()['candidates'][0]['content']['parts'][0]['text']
                ?? "Aucune réponse générée.";
        } catch (\Throwable $e) {
            Log::error('Erreur Gemini IA : ' . $e->getMessage());
            return "Erreur de l'IA lors de l’analyse des candidatures.";
        }
    }

    public static function analyserCandidaturesAvecOpenAI(string $texte): string
    {
        try {
            Log::info("Prompt envoyé à OpenAI : " . $texte);

            $apiKey = env('OPENAI_API_KEY');
            $client = OpenAI::client($apiKey);

            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $texte],
                ],
                'temperature' => 0.7,
            ]);

            // Le texte de la réponse
            $result = $response->choices[0]->message->content ?? '';

            return $result;

        } catch (\Throwable $e) {
            Log::error('Erreur OpenAI IA : ' . $e->getMessage());
            return "Erreur de l'IA lors de l’analyse des candidatures.";
        }
    }
}


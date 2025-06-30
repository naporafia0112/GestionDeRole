<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class OpenAIController extends Controller
{

public function analyser(OpenAIService $openai)
{
    $prompt = "Explique la différence entre Laravel et Symfony.";
    $reponse = $openai->envoyerPrompt($prompt);

    return view('openai.resultat', compact('reponse'));
}

}

<?php

namespace App\Http\Controllers;

use App\Models\Formulaire;
use App\Models\ChampFormulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stage;
use App\Models\ReponseFormulaire;
use App\Models\ReponseChamp;

class FormulaireController extends Controller
{
    public function create()
    {
        // Récupérer les stages (optionnel: filtrer selon besoin)
        $stages = Stage::all();

        return view('admin.rapports.directeur.formulairedynamique', compact('stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'stage_id' => 'required|exists:stages,id', // validation du stage
            'champs' => 'required|array|min:1',
            'champs.*.label' => 'required|string',
            'champs.*.type' => 'required|string|in:text,textarea,number,date,select,checkbox',
            'champs.*.requis' => 'boolean',
        ]);

        $user = Auth::user();

        $formulaire = Formulaire::create([
            'titre' => $request->titre,
            'id_departement' => $user->id_departement,
            'cree_par' => $user->id,
            'stage_id' => $request->stage_id,  // <-- association au stage
        ]);

        foreach ($request->champs as $champ) {
            ChampFormulaire::create([
                'formulaire_id' => $formulaire->id,
                'label' => $champ['label'],
                'type' => $champ['type'],
                'requis' => isset($champ['requis']),
                'options' => null,
            ]);
        }

        return redirect()->route('formulairedynamique.creation')->with('success', 'Formulaire créé avec succès.');
    }
    //PAGES TUTEURS
   public function affichageformulaire()
    {
        $user = Auth::user();

        // Récupère les stages supervisés par ce tuteur
        $stages = $user->stages()->with('formulaire')->get();

        return view('admin.rapports.tuteur.affichageformulaire', compact('stages'));
    }


    // Afficher le formulaire à remplir
    public function details(Formulaire $formulaire)
    {
        $formulaire->load('champs');

        return view('admin.rapports.tuteur.details', compact('formulaire'));
    }

    // Enregistrer la réponse du tuteur
    public function storereponse(Request $request, Formulaire $formulaire)
    {
        $user = Auth::user();

        // Validation des champs dynamiques
        $rules = [];
        foreach ($formulaire->champs as $champ) {
            $rule = [];
            if ($champ->requis) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }
            // Tu peux étendre selon le type, ex: 'date', 'numeric', etc.
            $rules["champs.{$champ->id}"] = $rule;
        }
        $request->validate($rules);

        // Création de la réponse globale
        $reponse = ReponseFormulaire::create([
            'formulaire_id' => $formulaire->id,
            'user_id' => $user->id,
        ]);

        // Enregistrement des réponses par champ
        foreach ($formulaire->champs as $champ) {
            ReponseChamp::create([
                'reponse_formulaire_id' => $reponse->id,
                'champ_formulaire_id' => $champ->id,
                'valeur' => $request->input("champs.{$champ->id}"),
            ]);
        }

        return redirect()->route('tuteur.formulaires.affichage')->with('success', 'Formulaire rempli avec succès.');
    }
    //PAGE DIRECTEUR
    //FORMULAIRES CRÉE PAR LE DIRECTEUR
    public function listeformulairesdirecteur()
    {
        $user = Auth::user();

        $formulaires = Formulaire::where('cree_par', $user->id)->get();

        return view('admin.rapports.directeur.liste', compact('formulaires'));
    }
    //LISTE réponses à un formulaire
    public function detailformdirecteur(Formulaire $formulaire)
    {
         $user = Auth::user();
        // Sécurité : seul le directeur qui l'a créé peut voir
        abort_if($formulaire->cree_par !== $user->id, 403);

        $formulaire->load(['reponses.tuteur', 'champs']);

        return view('admin.rapports.directeur.reponses', compact('formulaire'));
    }

    //DETAIL DE CHAQUE FORMULAIRES DE REPONSES
    public function reponseDetail(ReponseFormulaire $reponse)
    {
        $user = Auth::user();
        // Vérification que le directeur a bien créé ce formulaire
        abort_if($reponse->formulaire->cree_par !== $user->id, 403);

        $reponse->load('champs.champFormulaire');

        return view('admin.rapports.directeur.reponse-detail', compact('reponse'));
    }



}

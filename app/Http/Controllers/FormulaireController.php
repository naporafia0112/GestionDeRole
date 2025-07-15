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
        return view('admin.rapports.directeur.formulairedynamique');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
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

        return redirect()->route('directeur.formulaires.liste')->with('success', 'Formulaire créé avec succès.');
    }

    //PAGES TUTEURS
    public function affichageformulaire()
    {
        $user = Auth::user();

        // Récupérer tous les formulaires créés pour le département du tuteur
        $formulaires = Formulaire::where('id_departement', $user->id_departement)->get();

        return view('admin.rapports.tuteur.affichageformulaire', compact('formulaires'));
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

        $rules = [
            'stage_id' => 'required|exists:stages,id',
        ];

        foreach ($formulaire->champs as $champ) {
            $rule = $champ->requis ? ['required'] : ['nullable'];
            $rules["champs.{$champ->id}"] = $rule;
        }

        $request->validate($rules);

        $reponse = ReponseFormulaire::create([
            'formulaire_id' => $formulaire->id,
            'user_id' => $user->id,
            'stage_id' => $request->stage_id,
        ]);

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

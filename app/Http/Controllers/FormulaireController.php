<?php

namespace App\Http\Controllers;

use App\Models\Formulaire;
use App\Models\ChampFormulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stage;
use App\Models\ReponseFormulaire;
use App\Models\ReponseChamp;
use Illuminate\Validation\Rule;
use App\Notifications\NouveauFormulaireRapportNotification;
use App\Models\User;

class FormulaireController extends Controller
{
    public function create()
    {
        return view('admin.rapports.directeur.formulairedynamique');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'champs' => ['required', 'array'],
            'champs.*.label' => ['required', 'string', 'max:255'],
            'champs.*.type' => ['required', Rule::in(['text', 'textarea', 'number', 'date', 'checkbox', 'select', 'file'])],
            'champs.*.requis' => ['nullable', 'boolean'],
            'champs.*.options' => ['nullable', 'string'],
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
                'options' => in_array($champ['type'], ['select', 'checkbox']) ? $champ['options'] ?? null : null,            ]);
        }

       $tuteurs = User::whereHas('roles', function ($query) {
            $query->where('name', 'tuteur');
        })
        ->where('id_departement', $user->id_departement)
        ->get();

        foreach ($tuteurs as $tuteur) {
            $tuteur->notify(new NouveauFormulaireRapportNotification($formulaire));
        }
        return redirect()->route('directeur.formulaires.liste')->with('success', 'Formulaire créé avec succès.');
    }

    //PAGES TUTEURS
    public function affichageformulaire()
    {
        $user = Auth::user();

        // Récupérer tous les formulaires créés pour le département du tuteur
        $formulaires = Formulaire::where('id_departement', $user->id_departement)
        ->where('est_archive', false)->get();

        return view('admin.rapports.tuteur.affichageformulaire', compact('formulaires'));
    }



    // Afficher le formulaire à remplir
    public function details(Formulaire $formulaire)
    {
        $formulaire->load('champs');

        // Récupérer les stages liés au tuteur connecté (à adapter selon ton modèle)
        $user = Auth::user();
        $stages = Stage::where('id_tuteur', $user->id)->get();

        return view('admin.rapports.tuteur.details', compact('formulaire', 'stages'));
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
            $valeur = null;

            if ($champ->type === 'file' && $request->hasFile("champs.{$champ->id}")) {
                $fichier = $request->file("champs.{$champ->id}");
                $valeur = $fichier->store('formulaires_fichiers', 'public');
            } elseif ($champ->type === 'checkbox') {
                $valeur = is_array($request->input("champs.{$champ->id}"))
                    ? implode(', ', $request->input("champs.{$champ->id}"))
                    : null;
            } else {
                $valeur = $request->input("champs.{$champ->id}");
            }

            ReponseChamp::create([
                'reponse_formulaire_id' => $reponse->id,
                'champ_formulaire_id' => $champ->id,
                'valeur' => $valeur,
            ]);

        }
        $directeur = $formulaire->createur;
        $directeur->notify(new \App\Notifications\ReponseFormulaireSoumise($reponse));


        return redirect()->route('tuteur.formulaires.affichage')->with('success', 'Formulaire rempli avec succès.');
    }

    //PAGE DIRECTEUR
    //FORMULAIRES CRÉE PAR LE DIRECTEUR
    public function listeformulairesdirecteur()
    {
        $user = Auth::user();

        $formulaires = Formulaire::where('cree_par', $user->id)
        ->where('est_archive', false)
        ->get();

        return view('admin.rapports.directeur.liste', compact('formulaires'));
    }
    //LISTE réponses à un formulaire
   public function detailformdirecteur(Formulaire $formulaire)
    {
        $user = Auth::user();
        abort_if($formulaire->cree_par !== $user->id, 403);

        $formulaire->load(['reponses.tuteur', 'champs', 'reponses.stage.candidature.candidat']);
        foreach ($formulaire->reponses as $reponse) {
            $reponse->valide = $reponse->stage ? (bool) $reponse->stage->est_valide : false;
        }

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

    public function archiver(Formulaire $formulaire)
    {
        $formulaire->update(['est_archive' => true]);
        return redirect()->back()->with('success', 'Formulaire archivé avec succès.');
    }

    public function archives()
    {
        $formulaires = Formulaire::where('est_archive', true)->latest()->get();
        return view('admin.rapports.directeur.archives', compact('formulaires'));
    }

    public function edit(Formulaire $formulaire)
    {
        return view('admin.rapports.directeur.modifier', compact('formulaire'));
    }
    public function update(Request $request, Formulaire $formulaire)
    {
        $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'champs' => ['required', 'array'],
            'champs.*.label' => ['required', 'string', 'max:255'],
            'champs.*.type' => ['required', Rule::in(['text', 'textarea', 'number', 'date', 'checkbox', 'select', 'file'])],
            'champs.*.requis' => ['nullable', 'boolean'],
            'champs.*.options' => ['nullable', 'string'],
        ]);

        $formulaire->update([
            'titre' => $request->titre,
        ]);

        // Supprimer les anciens champs
        $formulaire->champs()->delete();

        // Recréer les nouveaux champs
        foreach ($request->champs as $champ) {
            ChampFormulaire::create([
                'formulaire_id' => $formulaire->id,
                'label' => $champ['label'],
                'type' => $champ['type'],
                'requis' => isset($champ['requis']),
                'options' => in_array($champ['type'], ['select', 'checkbox']) ? $champ['options'] ?? null : null,
            ]);
        }

        return redirect()->route('formulaires.archives')->with('success', 'Formulaire mis à jour avec succès.');
    }


    }

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\Localisation;
use App\Models\User;
use App\Models\Candidature;
class VitrineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offres = Offre::where('est_publie', true)->latest()->get();
        return view('vitrine.index', compact('offres'));
    }

    public function catalogue()
    {
        $offres = Offre::where('est_publie', true)->latest()->paginate(10);
        return view('vitrine.catalogue', compact('offres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Offre $offre)
    {
        if (!$offre->est_publie) {
            abort(404); // ou redirect avec erreur
        }

        return view('vitrine.show', compact('offre'));
    }

    public function detailcatalogue(Offre $offre)
    {
        if (!$offre->est_publie) {
            abort(404); // ou redirect avec erreur
        }

        return view('vitrine.detail-catalogue', compact('offre'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function recherche()
{
    return view('vitrine.consulter');
}
    public function suivi($uuid)
    {
        $candidature = Candidature::with('candidat')->where('uuid', $uuid)->firstOrFail();
        return view('vitrine.recherche', compact('candidature'));
    }

}

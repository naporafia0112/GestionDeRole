<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\{
    ProfileController,
    RoleController,
    OffreController,
    VitrineController,
    UserController,
    CandidatureController
};

// 🔒 Routes protégées (admin, gestion RH, etc.)
Route::middleware(['auth'])->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('offres', OffreController::class);

    Route::get('/offres/{offre}/candidatures', [CandidatureController::class, 'index'])->name('offres.candidatures');

    Route::get('/candidatures',           [CandidatureController::class, 'all'])->name('candidatures.index');
    Route::get('/candidatures/{id}/download/{field}', [CandidatureController::class, 'downloadFile'])->name('candidatures.download')->whereNumber('id');
    Route::get('/candidatures/{id}/preview/{field}', [CandidatureController::class, 'previewFile'])->name('candidatures.preview')->whereNumber('id');
    Route::get('/candidatures/{id}',      [CandidatureController::class, 'show'])->name('candidatures.show')->whereNumber('id');

    Route::post('offres/{offre}/publish', [OffreController::class, 'publish'])->name('offres.publish');
    Route::get('/candidatures/{id}/analyser-ia', [CandidatureController::class, 'analyserIA'])->name('candidatures.analyser_ia');

    Route::get('/dashboard', function () {
        return redirect()->route('offres.index');
    })->name('dashboard');

    Route::patch('/candidatures/{id}/rejeter', [CandidatureController::class, 'rejeter'])->name('candidatures.reject');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🌐 Routes publiques (vitrine et candidatures sans compte)
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/vitrine', [VitrineController::class, 'index'])->name('vitrine.index');
Route::get('/vitrine/{offre}', [VitrineController::class, 'show'])->name('vitrine.show');

Route::get('/offres/{id}/postuler', [CandidatureController::class, 'create'])->name('candidature.create');
Route::post('/offres/{id}/postuler', [CandidatureController::class, 'store'])->name('candidature.store');

// Suivi de candidature via UUID (publique)
Route::get('/candidatures/suivi/{uuid}', [CandidatureController::class, 'suivi'])->name('candidatures.suivi');

// Formulaire public pour rechercher une candidature par UUID
Route::post('/candidatures/recherche', action: [CandidatureController::class, 'recherche'])->name('candidatures.recherche');
Route::get('/catalogue', [VitrineController::class, 'catalogue'])->name('vitrine.catalogue');


require __DIR__.'/auth.php';

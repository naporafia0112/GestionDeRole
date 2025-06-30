<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    RoleController,
    OffreController,
    VitrineController,
    UserController,
    CandidatureController,
    EntretienController,
    DashboardController,
    OpenAIController
};
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Routes publiques (pas d'auth)
Route::get('/', [VitrineController::class, 'index'])->name('vitrine.index');
Route::get('/vitrine/{offre}', [VitrineController::class, 'show'])->name('vitrine.show');
Route::get('/catalogue', [VitrineController::class, 'catalogue'])->name('vitrine.catalogue');

Route::get('/offres/{id}/postuler', [CandidatureController::class, 'create'])->name('candidature.create');
Route::post('/offres/{id}/postuler', [CandidatureController::class, 'store'])->name('candidature.store');

Route::get('/candidatures/suivi/{uuid}', [CandidatureController::class, 'suivi'])->name('candidatures.suivi');
Route::post('/candidatures/recherche', [CandidatureController::class, 'recherche'])->name('candidatures.recherche');

// Routes accessibles uniquement aux utilisateurs connectés
Route::middleware(['auth'])->group(function () {

    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Candidatures générales
    Route::get('/candidatures', [CandidatureController::class, 'all'])->name('candidatures.index');
    Route::get('/candidatures/{id}/download/{field}', [CandidatureController::class, 'downloadFile'])
        ->name('candidatures.download')->whereNumber('id');
    Route::get('/candidatures/{id}/preview/{field}', [CandidatureController::class, 'previewFile'])
        ->name('candidatures.preview')->whereNumber('id');
    Route::get('/candidatures/{id}', [CandidatureController::class, 'show'])
        ->name('candidatures.show')->whereNumber('id');
    Route::get('/candidatures/{id}/analyser-ia', [CandidatureController::class, 'analyserIA'])
        ->name('candidatures.analyser_ia');
    Route::post('/candidatures/analyser', [CandidatureController::class, 'analyser'])
        ->name('candidatures.analyser');

    // Actions sur les candidatures : rejeter, retenir, valider, effectuer
    Route::patch('/candidatures/{id}/rejeter', [CandidatureController::class, 'rejeter'])
        ->name('candidatures.reject');
    Route::patch('/candidatures/{id}/retenir', [CandidatureController::class, 'retenir'])
        ->name('candidatures.retenir');
    Route::patch('/candidatures/{id}/valider', [CandidatureController::class, 'valider'])
        ->name('candidatures.valider');
    Route::patch('/candidatures/{id}/effectuee', [CandidatureController::class, 'effectuee'])
        ->name('candidatures.effectuee');

    // Liste candidatures d'une offre (pour admin/RH)
    Route::get('/offres/{offre}/candidatures', [CandidatureController::class, 'index'])
        ->name('offres.candidatures');

    // Routes Entretiens accessibles uniquement aux RH et ADMIN
    Route::middleware(['role:RH,ADMIN'])->prefix('entretiens')->name('entretiens.')->group(function () {
        Route::get('/', [EntretienController::class, 'index'])->name('index');
        Route::get('/calendrier', [EntretienController::class, 'calendrier'])->name('calendrier');
        Route::get('/events', [EntretienController::class, 'getEvents'])->name('events');
        Route::get('/create', [EntretienController::class, 'create'])->name('create');
        Route::post('/store', [EntretienController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EntretienController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EntretienController::class, 'update'])->name('update');
        Route::delete('/{id}', [EntretienController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/show', [EntretienController::class, 'show'])->name('show');
        Route::patch('/{id}/annuler', [EntretienController::class, 'annuler'])->name('annuler');
        Route::post('/action', [EntretienController::class, 'action'])->name('action');
        Route::get('/entretiens/{id}/show-json', [EntretienController::class, 'showJson'])->name('entretiens.show-json');
    });

    // Routes Offres accessibles aux RH et ADMIN
    Route::middleware(['role:RH,ADMIN'])->group(function () {
        Route::resource('offres', OffreController::class);
        Route::post('offres/{offre}/publish', [OffreController::class, 'publish'])->name('offres.publish');
    });

    // Routes réservées aux ADMIN uniquement (gestion utilisateurs et rôles)
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('user', UserController::class);
    });
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// 🔐 Auth routes (login, logout, register, reset, etc.)
require __DIR__ . '/auth.php';

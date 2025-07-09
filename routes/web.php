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
    StageController,
    OllamaTestController,
    CVAnalyzerController,
    DepartementController,
    RapportController
};
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjetCreateMail;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Routes publiques
Route::get('/', [VitrineController::class, 'index'])->name('vitrine.index');
Route::get('/vitrine/{offre}', [VitrineController::class, 'show'])->name('vitrine.show');
Route::get('/catalogue', [VitrineController::class, 'catalogue'])->name('vitrine.catalogue');

Route::get('/offres/{id}/postuler', [CandidatureController::class, 'create'])->name('candidature.create');
Route::post('/offres/{id}/postuler', [CandidatureController::class, 'store'])->name('candidature.store');

Route::get('/candidatures/suivi/{uuid}', [VitrineController::class, 'suivi'])->name('candidatures.suivi');
Route::post('/candidatures/recherche', [CandidatureController::class, 'recherche'])->name('candidatures.recherche');

// Routes accessibles uniquement aux utilisateurs connectés
Route::middleware(['auth'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/directeur', [DashboardController::class, 'dashboardDirecteur'])->name('dashboard.directeur');
    Route::get('/dashboard/rh', [DashboardController::class, 'dashboardRH'])->name('dashboard.RH');
    Route::get('/dashboard/tuteur', [DashboardController::class, 'dashboardTuteur'])->name('dashboard.tuteur');

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


    // Actions sur les candidatures : rejeter, retenir, valider, effectuer
    Route::patch('/candidatures/{id}/rejeter', [CandidatureController::class, 'rejeter'])
        ->name('candidatures.reject');
    Route::patch('/candidatures/{id}/retenir', [CandidatureController::class, 'retenir'])
        ->name('candidatures.retenir');
    Route::post('/candidatures/{id}/valider', [CandidatureController::class, 'valider'])
        ->name('candidatures.valider');
    Route::patch('/candidatures/{id}/effectuee', [CandidatureController::class, 'effectuee'])
        ->name('candidatures.effectuee');
    Route::post('/candidatures/{id}/analyze', [CandidatureController::class, 'analyze'])->name('candidatures.analyze');
    Route::get('candidatures/retenus', [CandidatureController::class, 'dossiersRetenus'])->name('candidatures.retenus');
    Route::get('candidatures/valides', [CandidatureController::class, 'dossiersValides'])->name('candidatures.valides');
    Route::post('/offres/{offre}/preselectionner', [CandidatureController::class, 'preselectionner'])->name('candidatures.preselectionner');

    // Routes personnalisées pour les types de stages

    Route::get('stages/academiques', [StageController::class, 'stagesAcademiques'])->name('stages.academiques');
    Route::get('stages/professionnels', [StageController::class, 'stagesProfessionnels'])->name('stages.professionnels');
    Route::get('stages/preembauche', [StageController::class, 'stagesPreembauche'])->name('stages.preembauche');
    Route::middleware(['auth', 'role:DIRECTEUR'])->group(function () {
    Route::get('/directeur/stages', [StageController::class, 'stagesParDepartement'])->name('directeur.stages');
    });
    Route::post('/stages/{stage}/affecter-tuteur', [StageController::class, 'affecterTuteur'])->name('stages.affecterTuteur');

    Route::get('/directeur/stages/en-cours', [StageController::class, 'stagesAvecTuteur'])->name('stages.en_cours');
    Route::get('/directeur/tuteurs', [StageController::class, 'listerTuteursDepartement'])->name('directeur.tuteurs');
    Route::get('/directeur/candidats-stages-en-cours', [StageController::class, 'candidatsStagesEnCours'])->name('stages.candidats_en_cours');
    Route::get('/rh/stages/attente-tuteur', [StageController::class, 'stagesEnAttentePourRH'])
    ->name('rh.stages.attente_tuteur');
    Route::get('/rh/stages/en-cours', [StageController::class, 'stagesEnCoursPourRH'])
    ->name('rh.stages.en_cours');
    Route::get('/stages/rh/candidats-en-stage', [StageController::class, 'candidatsEnStage'])
        ->name('stages.rh.candidats_en_stage');
    Route::get('/tuteur/liste-candidats', [StageController::class, 'candidatsTuteur'])->name('stages.candidats_tuteurs');
    Route::get('/candidats/{id}/details/tuteur', [StageController::class, 'details_candidat_encours_tuteur'])->name('candidats.details');
    Route::get('/candidats/{id}/details/directeur', [StageController::class, 'details_candidat_encours_directeur'])->name('candidats.details.directeur');

    // Ensuite seulement, ajoute la ressource générale
    Route::resource('stages', StageController::class);

    Route::get('/tuteurs', [StageController::class, 'affecterTuteur'])->name('tuteurs.afficher');


    // Liste candidatures d'une offre (pour admin/RH)
    Route::get('/offres/{offre}/candidatures', [CandidatureController::class, 'index'])
        ->name('offres.candidatures');
    Route::get('/rapports/export', [RapportController::class, 'form'])->name('rapport.form');
    Route::get('/rapports/generer', [RapportController::class, 'generer'])->name('rapport.generer');

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
        Route::get('/{id}/show-json', [EntretienController::class, 'showJson'])->name('show-json');
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
        Route::resource('departements', DepartementController::class)->except(['show', 'edit', 'create']);
    });

    Route::get('/cv/analyze', [CVAnalyzerController::class, 'form'])->name('cv.form');
    Route::post('/cv/analyze', [CVAnalyzerController::class, 'analyze'])->name('cv.analyze');

});

Route::prefix('ollama')->group(function () {
    Route::get('/test', [OllamaTestController::class, 'index'])->name('ollama.test');
    Route::get('/connection', [OllamaTestController::class, 'testConnection']);
    Route::get('/models', [OllamaTestController::class, 'listModels']);
    Route::post('/analyze', [OllamaTestController::class, 'analyzeCV']);
    Route::get('/sample-test', [OllamaTestController::class, 'testWithSampleCV']);
    Route::get('/preselection-test', [OllamaTestController::class, 'preselectionTest']);
});

Route::get('/test-mail', function () {
    $data = [
        'titre' => 'Projet Test',
        'description' => 'Ceci est un projet de test sans base de données.',
        'objectifs' => 'Envoyer un email sans utiliser Eloquent.'
    ];

    Mail::to('naporafia0@gmail.com')->send(new ProjetCreateMail($data));

    return "Email envoyé avec succès !";
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Auth routes (login, logout, register, reset, etc.)
require __DIR__ . '/auth.php';

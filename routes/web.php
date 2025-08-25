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
    RapportController,
    FormulaireController,
    CandidatureSpontaneeController,
    PermissionController,
    PasswordChangeController,
    NotificationController,
    AttestationController
};
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjetCreateMail;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Routes publiques
Route::get('/', [VitrineController::class, 'index'])->name('vitrine.index');
Route::get('/vitrine/{offre}', [VitrineController::class, 'show'])->name('vitrine.show');
Route::get('/catalogue', [VitrineController::class, 'catalogue'])->name('vitrine.catalogue');
Route::get('/vitrine/catalogue/{offre}', [VitrineController::class, 'detailcatalogue'])->name('vitrine.detailcatalogue');

Route::get('/offres/{id}/postuler', [CandidatureController::class, 'create'])->name('candidature.create');
Route::post('/offres/{id}/postuler', [CandidatureController::class, 'store'])->name('candidature.store');

Route::match(['get', 'post'], '/candidatures/suivi/{uuid?}', [VitrineController::class, 'suivi'])->name('candidatures.suivi');
Route::post('/candidatures/recherche', [CandidatureController::class, 'recherche'])->name('candidatures.recherche');
Route::get('/candidature-spontanee', [CandidatureSpontaneeController::class, 'create'])->name('candidature.spontanee.form');
Route::post('/candidature-spontanee', [CandidatureSpontaneeController::class, 'store'])->name('candidature.spontanee.store');
Route::post('/candidature/renvoi-email', [CandidatureController::class, 'renvoyerEmail'])->name('candidature.renvoi.email');
// Routes accessibles uniquement aux utilisateurs connectés
Route::middleware(['auth'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/directeur', [DashboardController::class, 'dashboardDirecteur'])->name('dashboard.directeur');
    Route::get('/dashboard/rh', [DashboardController::class, 'dashboardRH'])->name('dashboard.RH');
    Route::get('/dashboard/tuteur', [DashboardController::class, 'dashboardTuteur'])->name('dashboard.tuteur');
    Route::post('/admin/export-graphes', [DashboardController::class, 'exportGraph'])->name('graph.export');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil.show');


    // Candidatures générales
    Route::get('/candidatures', [CandidatureController::class, 'all'])->name('candidatures.index');
    Route::get('/candidatures/{id}/preview/{field}', [CandidatureController::class, 'preview'])->name('candidatures.preview');
    Route::get('/candidatures/{id}/download/{field}', [CandidatureController::class, 'downloadFile'])->name('candidatures.download');

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

    //CANDIDATURES SPONTANNÉES

    Route::get('/admin/candidatures-spontanees', [CandidatureSpontaneeController::class, 'index'])
        ->name('admin.candidatures.spontanees.index');
    Route::get('/candidatures/spontanees/{id}', [CandidatureSpontaneeController::class, 'show'])
        ->name('candidatures.spontanees.show')->whereNumber('id');
    Route::patch('candidatures-spontanees/{id}/valider', [CandidatureSpontaneeController::class, 'valider'])->name('candidatures.spontanees.valider');
    Route::patch('/candidatures-spontanees/{id}/retenir', [CandidatureSpontaneeController::class, 'retenir'])
        ->name('candidatures.spontanees.retenir');
    Route::patch('candidatures-spontanees/{id}/rejeter', [CandidatureSpontaneeController::class, 'rejeter'])->name('candidatures.spontanees.rejeter');
    Route::post('candidatures-spontanees/{id}/analyze', [CandidatureSpontaneeController::class, 'analyze'])->name('candidatures.spontanees.analyze');
    // Aperçu PDF dans navigateur
    Route::get('/candidatures-spontanees/{id}/preview/{field}', [CandidatureSpontaneeController::class, 'preview'])->name('candidaturespontanee.preview');
    Route::get('/candidatures-spontanees/{id}/download/{field}', [CandidatureSpontaneeController::class, 'download'])->name('candidaturespontanee.download');


    // Routes personnalisées pour les types de stages

    Route::get('stages/academiques', [StageController::class, 'stagesAcademiques'])->name('stages.academiques');
    Route::get('stages/professionnels', [StageController::class, 'stagesProfessionnels'])->name('stages.professionnels');
    Route::get('stages/preembauche', [StageController::class, 'stagesPreembauche'])->name('stages.preembauche');

    // Routes du DIRECTEUR
    Route::middleware(['role:DIRECTEUR'])->group(function () {
    Route::get('/directeur/stages', [StageController::class, 'stagesParDepartement'])->name('directeur.stages');
    Route::get('/formulaires/create', [FormulaireController::class, 'create'])->name('formulairedynamique.creation');
    Route::post('/formulaires', [FormulaireController::class, 'store'])->name('formulaires.store');
    Route::post('/stages/{stage}/affecter-tuteur', [StageController::class, 'affecterTuteur'])->name('stages.affecterTuteur');
    Route::get('/directeur/formulaires', [FormulaireController::class, 'listeformulairesdirecteur'])->name('directeur.formulaires.liste');
    Route::get('/directeur/formulaires/{formulaire}', [FormulaireController::class, 'detailformdirecteur'])->name('directeur.formulaires.reponses');
    Route::get('/directeur/reponses/{reponse}', [FormulaireController::class, 'reponseDetail'])->name('directeur.reponses.details');
    Route::get('/formulaires/archives', [FormulaireController::class, 'archives'])->name('formulaires.archives');
    Route::patch('/formulaires/{formulaire}/archiver', [FormulaireController::class, 'archiver'])->name('formulaires.archiver');
    Route::get('/formulaires/{formulaire}/edit', [FormulaireController::class, 'edit'])->name('formulaires.edit');
    Route::put('/formulaires/{formulaire}', [FormulaireController::class, 'update'])->name('formulaires.update');
    Route::get('/formulaires/{formulaire}/preview', [FormulaireController::class, 'preview'])->name('formulaires.preview');
    Route::patch('/formulaires/{id}/restore', [FormulaireController::class, 'restore'])
    ->name('directeur.formulaires.restore');
    Route::patch('/reponses-formulaire/{reponse}/valider', [FormulaireController::class, 'validerParDirecteur'])
    ->name('reponses.valider');

    });

    Route::get('/directeur/stages/en-cours', [StageController::class, 'stagesAvecTuteur'])->name('stages.en_cours');
    Route::get('/stages/termines', [StageController::class, 'stagesTermines'])->name('stages.termines');
    Route::get('/directeur/tuteurs', [StageController::class, 'listerTuteursDepartement'])->name('directeur.tuteurs');
    Route::get('/directeur/candidats-stages-en-cours', [StageController::class, 'candidatsStagesEnCours'])->name('stages.candidats_en_cours');
    Route::get('/rh/stages/attente-tuteur', [StageController::class, 'stagesEnAttentePourRH'])
    ->name('rh.stages.attente_tuteur');
    Route::get('/rh/stages/en-cours', [StageController::class, 'stagesEnCoursPourRH'])
    ->name('rh.stages.en_cours');
    Route::get('/rh/stages/termines', [StageController::class, 'stagesTerminesPourRH'])->name('rh.stages.termines');
    Route::get('/tuteur/stages/en-cours', [StageController::class, 'stagesEnCoursPourtuteur'])
    ->name('tuteur.stages.en_cours');
    Route::get('/tuteur/stages/termines', [StageController::class, 'stagesTerminesPourTuteur'])->name('tuteur.stages.termines');
    Route::get('/stages/rh/candidats-en-stage', [StageController::class, 'candidatsEnStage'])
        ->name('stages.rh.candidats_en_stage');
    Route::get('/export-candidats-base', [StageController::class, 'exportTous'])->name('candidats.export.tous');
    Route::get('/export-candidats-pdf', [StageController::class, 'exportPDF'])->name('candidats.export.pdf');
    Route::get('/export-candidats-word', [StageController::class, 'exportWord'])->name('candidats.export.word');
    Route::get('/imprimer-candidats', [StageController::class, 'imprimer'])->name('candidats.imprimer');
    Route::get('/tuteur/liste-candidats', [StageController::class, 'candidatsTuteur'])->name('stages.candidats_tuteurs');
    Route::get('/candidats/{id}/details/tuteur', [StageController::class, 'details_candidat_encours_tuteur'])->name('candidats.details');
    Route::get('/candidats/{id}/details/directeur', [StageController::class, 'details_candidat_encours_directeur'])->name('candidats.details.directeur');
    Route::get('/directeur/stages/{stage}', [StageController::class, 'detailstagedirecteur'])
    ->name('directeur.stages.details');
    Route::get('/tuteur/stages/{stage}', [StageController::class, 'detailstagetuteur'])
    ->name('tuteur.stages.details');
    Route::patch('/stages/{reponse}/valider-par-directeur', [StageController::class, 'validerParDirecteur'])
    ->name('stages.valider_par_directeur');

    // Ensuite seulement, ajoute la ressource générale
    Route::resource('stages', StageController::class);

    Route::get('/tuteurs', [StageController::class, 'affecterTuteur'])->name('tuteurs.afficher');

    Route::get('/tuteur/formulaires', [FormulaireController::class, 'affichageformulaire'])->name('tuteur.formulaires.affichage');
    Route::get('/tuteur/formulaires/{formulaire}', [FormulaireController::class, 'details'])->name('tuteur.formulaires.details');
    Route::post('/tuteur/formulaires/{formulaire}', [FormulaireController::class, 'storereponse'])->name('tuteur.formulaires.store');
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
        Route::get('/entretiens', [EntretienController::class, 'liste_entretiens_export'])->name('liste');
        // web.php
        Route::get('/slots', [EntretienController::class, 'slots'])->name('slots');
        Route::get('/creneaux', [EntretienController::class, 'showSlotsPage'])->name('slots.page');
    });

    // Routes Offres accessibles aux RH et ADMIN
    Route::middleware(['role:RH,ADMIN'])->group(function () {
        Route::resource('offres', OffreController::class);
        Route::post('offres/{offre}/publish', [OffreController::class, 'publish'])->name('offres.publish');
    });

    // Routes réservées aux ADMIN uniquement (gestion utilisateurs et rôles)
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('user', UserController::class);
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('user.toggleActive');
        Route::resource('departements', DepartementController::class)->except(['show', 'edit', 'create']);
    });

    Route::get('/cv/analyze', [CVAnalyzerController::class, 'form'])->name('cv.form');
    Route::post('/cv/analyze', [CVAnalyzerController::class, 'analyze'])->name('cv.analyze');

    // Routes pour les attestations

    Route::prefix('attestations')->name('attestations.')->group(function () {
    Route::get('/liste', [AttestationController::class, 'index'])->name('liste');
    Route::get('/create', [AttestationController::class, 'create'])->name('create');
    Route::post('/', [AttestationController::class, 'store'])->name('store');
    Route::get('/{attestation}', [AttestationController::class, 'show'])->name('show');
    Route::get('/{attestation}/pdf', [AttestationController::class, 'exportPDF'])->name('export.pdf');
    Route::get('/{attestation}/word', [AttestationController::class, 'exportWord'])->name('export.word');
});


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
Route::get('/changer-mot-de-passe', [PasswordChangeController::class, 'edit'])->name('password.change.form');
Route::put('/changer-mot-de-passe', [PasswordChangeController::class, 'update'])->name('password.change.update');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');


// Auth routes (login, logout, register, reset, etc.)
require __DIR__ . '/auth.php';

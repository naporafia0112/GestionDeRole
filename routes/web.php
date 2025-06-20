<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\VitrineController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CandidatureController;


Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('offres', OffreController::class);
    Route::get('/candidatures',           [CandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('/candidatures/{id}',      [CandidatureController::class, 'show'])->name('candidatures.show');
    Route::get('/candidatures/{id}/{f}',  [CandidatureController::class, 'downloadFile'])->name('candidatures.download');
});

Route::get('/offres/{id}/postuler', [CandidatureController::class, 'create'])
     ->name('candidature.create');
Route::post('/offres/{id}/postuler', [CandidatureController::class, 'store'])
     ->name('candidature.store');

Route::post('offres/{offre}/publish', [OffreController::class, 'publish'])->name('offres.publish');

Route::get('/vitrine', [VitrineController::class, 'index'])->name('vitrine.index');
Route::get('/vitrine/{offre}', [VitrineController::class, 'show'])->name('vitrine.show');

Route::get('/recherche', [VitrineController::class, 'recherche'])->name('vitrine.consulter');

Route::get('/dashboard', function () {
return redirect()->route('offres.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

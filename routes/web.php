<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\VitrineController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('offres', OffreController::class);
});

Route::get('/', function () {
    return view('auth.login');
});


Route::get('/vitrine', [VitrineController::class, 'index'])->name('vitrine.index');


Route::post('offres/{offre}/publish', [OffreController::class, 'publish'])->name('offres.publish');


Route::get('/dashboard', function () {
return redirect()->route('user.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Stage;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.home', function ($view) {
        $directeur = Auth::user();

        $nombreStagesAttente = 0;
        if ($directeur && $directeur->hasRole('DIRECTEUR')) {
            $nombreStagesAttente = Stage::whereNull('id_tuteur')
                ->where('id_departement', $directeur->id_departement)
                ->count();
        }

        $view->with('nombreStagesAttente', $nombreStagesAttente);
    });
    }
}

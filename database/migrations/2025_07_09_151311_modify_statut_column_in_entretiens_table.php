<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('entretiens', function (Blueprint $table) {
            // Modifier la colonne statut : changer la valeur par défaut en 'prevu' sans accent
            $table->string('statut')->default('prevu')->change();
        });

        // Optionnel : Mettre à jour toutes les lignes existantes où statut = 'prévu' (avec accent)
        DB::table('entretiens')->where('statut', 'prévu')->update(['statut' => 'prevu']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entretiens', function (Blueprint $table) {
            // Revenir à la valeur par défaut précédente avec accent
            $table->string('statut')->default('prévu')->change();
        });

        // Optionnel : revenir sur les statuts modifiés
        DB::table('entretiens')->where('statut', 'prevu')->update(['statut' => 'prévu']);
    }
};

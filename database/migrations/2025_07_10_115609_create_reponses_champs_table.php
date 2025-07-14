<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reponses_champs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reponse_formulaire_id')->constrained('reponses_formulaires')->onDelete('cascade');
            $table->foreignId('champ_formulaire_id')->constrained('champs_formulaires')->onDelete('cascade');
            $table->text('valeur')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reponses_champs');
    }
};

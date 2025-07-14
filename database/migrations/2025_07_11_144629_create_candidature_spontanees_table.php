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
        Schema::create('candidatures_spontanees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidat_id')->constrained('candidats')->onDelete('cascade');

            $table->date('date_soumission')->useCurrent();
            $table->string('statut')->default('reçue');

            $table->string('cv_fichier')->nullable();
            $table->string('lm_fichier')->nullable();
            $table->string('lr_fichier')->nullable();

            $table->text('message')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidature_spontanees');
    }
};

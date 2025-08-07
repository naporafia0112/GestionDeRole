<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameStagiaireIdToStageIdInAttestationsTable extends Migration
{
    public function up(): void
    {
        Schema::table('attestations', function (Blueprint $table) {
            // D'abord, supprimer la clé étrangère existante
            $table->dropForeign(['stagiaire_id']);

            // Renommer la colonne
            $table->renameColumn('stagiaire_id', 'stage_id');

            // Puis recréer la clé étrangère avec le nouveau nom
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('attestations', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->renameColumn('stage_id', 'stagiaire_id');
            $table->foreign('stagiaire_id')->references('id')->on('stages')->onDelete('cascade');
        });
    }
}


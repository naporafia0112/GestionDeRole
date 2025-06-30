<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();

            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();

            $table->foreignId('id_candidat')->constrained('candidats')->onDelete('cascade');
            $table->foreignId('id_tuteur')->nullable()->constrained('users')->onDelete('set null');

            $table->string('sujet');
            $table->string('lieu')->nullable();
            $table->string('statut');
            $table->string('departement')->nullable();
            $table->integer('note_finale')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};

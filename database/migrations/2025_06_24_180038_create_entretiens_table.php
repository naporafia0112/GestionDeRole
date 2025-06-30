<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('entretiens', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('heure');
            $table->string('lieu');
            $table->string('type');
            $table->string('statut')->default('prévu');
            $table->text('commentaire')->nullable();
            $table->unsignedBigInteger('id_candidat');
            $table->unsignedBigInteger('id_offre');
            $table->timestamps();

            $table->foreign('id_candidat')->references('id')->on('candidats')->onDelete('cascade');
            $table->foreign('id_offre')->references('id')->on('offres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entretiens');
    }
};

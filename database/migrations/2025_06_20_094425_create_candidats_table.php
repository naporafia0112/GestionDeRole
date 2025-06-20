<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('candidats', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('quartier')->nullable();
            $table->string('ville')->nullable();
            $table->enum('type_depot', ['stage professionnel', 'stage académique', 'stage de préembauche']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('candidats');
    }
};

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
        Schema::create('champs_formulaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulaire_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->string('type');
            $table->boolean('requis')->default(false);
            $table->json('options')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('champs_formulaires');
    }
};

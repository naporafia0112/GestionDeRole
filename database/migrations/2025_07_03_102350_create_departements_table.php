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
       Schema::create('departements', function (Blueprint $table) {
        $table->id();
        $table->string('nom')->unique();
        $table->text('description')->nullable();
        $table->unsignedBigInteger('id_directeur')->nullable();
        $table->foreign('id_directeur')->references('id')->on('users')->onDelete('set null');
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departements');
    }
};

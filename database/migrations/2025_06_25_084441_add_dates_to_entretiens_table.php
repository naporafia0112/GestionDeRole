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
        Schema::table('entretiens', function (Blueprint $table) {
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('entretiens', function (Blueprint $table) {
            $table->dropColumn(['date_debut', 'date_fin']);
        });
    }

};

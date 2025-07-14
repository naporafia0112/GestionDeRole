<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('stages', function (Blueprint $table) {
            $table->boolean('validation_directeur')->default(false)->after('note_finale');
        });
    }

    public function down(): void {
        Schema::table('stages', function (Blueprint $table) {
            $table->dropColumn('validation_directeur');
        });
    }
};

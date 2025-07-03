<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->integer('score')->nullable()->after('statut');
            $table->text('commentaire')->nullable()->after('score');
        });
    }

    public function down(): void {
        Schema::table('candidatures', function (Blueprint $table) {
            $table->dropColumn(['score', 'commentaire']);
        });
    }
};

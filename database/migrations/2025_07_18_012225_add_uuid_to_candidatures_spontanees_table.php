<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidatures_spontanees', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->nullable()->after('id');
        });

        // Remplir les UUID pour les anciennes lignes
        DB::table('candidatures_spontanees')->whereNull('uuid')->get()->each(function ($item) {
            DB::table('candidatures_spontanees')
                ->where('id', $item->id)
                ->update(['uuid' => Str::uuid()]);
        });

        // Rendre la colonne non nullable
        Schema::table('candidatures_spontanees', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('candidatures_spontanees', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};

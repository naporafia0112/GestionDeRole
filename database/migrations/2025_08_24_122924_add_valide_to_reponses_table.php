<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reponses_formulaires', function (Blueprint $table) {
            $table->boolean('valide')->default(false)->after('updated_at'); // booléen par défaut false
        });
    }

    public function down()
    {
        Schema::table('reponses_formulaires', function (Blueprint $table) {
            $table->dropColumn('valide');
        });
    }
};

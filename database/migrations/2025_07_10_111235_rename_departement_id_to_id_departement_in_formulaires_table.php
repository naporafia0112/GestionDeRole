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
        Schema::table('formulaires', function (Blueprint $table) {
            $table->renameColumn('departement_id', 'id_departement');
        });
    }

    public function down()
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->renameColumn('id_departement', 'departement_id');
        });
    }
};

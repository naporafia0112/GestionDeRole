<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeIdOffreNullableInEntretiensTable extends Migration
{
    public function up()
    {
        Schema::table('entretiens', function (Blueprint $table) {
            $table->unsignedBigInteger('id_offre')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('entretiens', function (Blueprint $table) {
            $table->unsignedBigInteger('id_offre')->nullable(false)->change();
        });
    }
}

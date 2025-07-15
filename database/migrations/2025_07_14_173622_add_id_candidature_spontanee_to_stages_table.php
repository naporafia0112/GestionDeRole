<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCandidatureSpontaneeToStagesTable extends Migration
{
    public function up()
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->unsignedBigInteger('id_candidature_spontanee')->nullable()->after('id_candidature');

            $table->foreign('id_candidature_spontanee')
                ->references('id')
                ->on('candidatures_spontanees')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->dropForeign(['id_candidature_spontanee']);
            $table->dropColumn('id_candidature_spontanee');
        });
    }
}

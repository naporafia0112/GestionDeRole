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
            $table->unsignedBigInteger('stage_id')->nullable()->after('id');
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('formulaires', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->dropColumn('stage_id');
        });
    }

};

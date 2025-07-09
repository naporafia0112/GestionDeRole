<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStagesTableAddFields extends Migration
{
    public function up()
    {
        Schema::table('stages', function (Blueprint $table) {
            // Supprimer id_candidat si existant
            if (Schema::hasColumn('stages', 'id_candidat')) {
                $table->dropForeign(['id_candidat']);
                $table->dropColumn('id_candidat');
            }

            // Ajouter id_candidature si absent
            if (!Schema::hasColumn('stages', 'id_candidature')) {
                $table->unsignedBigInteger('id_candidature')->nullable()->after('id_tuteur');
                $table->foreign('id_candidature')->references('id')->on('candidatures')->onDelete('cascade');
            }

            // Ajouter remuneration
            if (!Schema::hasColumn('stages', 'remuneration')) {
                $table->decimal('remuneration', 8, 2)->nullable();
            }

            // Modifier note_finale en float si ce n’est pas déjà le cas
            $table->float('note_finale')->nullable()->change();

            // Ajouter id_departement
            if (!Schema::hasColumn('stages', 'id_departement')) {
                $table->unsignedBigInteger('id_departement')->nullable()->after('statut');
                $table->foreign('id_departement')->references('id')->on('departements')->onDelete('set null');
            }

            // Modifier statut en string au lieu d’enum
            $table->string('statut', 50)->default('en_attente')->change();
        });
    }

    public function down()
    {
        Schema::table('stages', function (Blueprint $table) {
            // Revenir en arrière sur statut
            $table->string('statut')->change();

            if (Schema::hasColumn('stages', 'id_departement')) {
                $table->dropForeign(['id_departement']);
                $table->dropColumn('id_departement');
            }

            // Revenir note_finale en integer si besoin
            $table->integer('note_finale')->nullable()->change();

            if (Schema::hasColumn('stages', 'remuneration')) {
                $table->dropColumn('remuneration');
            }

            if (!Schema::hasColumn('stages', 'id_candidat')) {
                $table->unsignedBigInteger('id_candidat')->nullable()->after('id_tuteur');
                $table->foreign('id_candidat')->references('id')->on('candidats')->onDelete('cascade');
            }

            if (Schema::hasColumn('stages', 'id_candidature')) {
                $table->dropForeign(['id_candidature']);
                $table->dropColumn('id_candidature');
            }
        });
    }
}

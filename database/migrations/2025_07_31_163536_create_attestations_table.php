<?php

// database/migrations/xxxx_xx_xx_create_attestations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttestationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('attestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stagiaire_id')->constrained('stages')->onDelete('cascade');
            $table->enum('type', ['academique', 'professionnel']);
            $table->string('service');
            $table->date('debut');
            $table->date('fin');
            $table->date('date_generation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attestations');
    }
}


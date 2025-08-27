<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investigacion_autor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investigacion_id')->index()->constrained('investigaciones')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('autor_id')->index()->constrained('autors')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('rol', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigacion_autor');
    }
};

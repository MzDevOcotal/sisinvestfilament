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
        Schema::create('investigaciones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->integer('avance');
            $table->foreignId('area_id')->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sede_id')->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('categoria_id')->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('year_id')->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('enfoque_id')->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('estado_id')->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('linea_id')->index()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('PDF', 300);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigaciones');
    }
};

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
        Schema::table('autors', function (Blueprint $table) {
            $table->enum('Estado', ['Activo', 'Suspendido', 'Baja'])->default('Activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('autors', function (Blueprint $table) {
            $table->dropColumn('Estado');
        });
    }
};

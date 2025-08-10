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
        Schema::table('users', function (Blueprint $table) {
        $table->string('avatar', 255)->nullable()->default('imginvestigadores/ursdflt.jpg');
        $table->string('phone', 15)->nullable();
        $table->string('address', 255)->nullable();
        $table->longtext('profile_description')->nullable();
        $table->string('department', 30)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn(['avatar', 'phone', 'address', 'profile_description', 'department']);
        });
    }
};
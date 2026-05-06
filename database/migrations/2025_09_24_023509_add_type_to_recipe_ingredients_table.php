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
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->enum('type', ['main', 'optional'])->default('main')->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
        $table->id();
        $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
        $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
        $table->string('quantity')->nullable(); // e.g. "2", "1/2"
        $table->string('unit')->nullable(); // e.g. "pcs", "tbsp"
        $table->timestamps();

        $table->unique(['recipe_id','ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};

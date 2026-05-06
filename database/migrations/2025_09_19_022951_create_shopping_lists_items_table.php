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
        Schema::create('shopping_list_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('shopping_list_id')->constrained('shopping_lists')->onDelete('cascade');
        $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
        $table->string('quantity')->nullable();
        $table->string('unit')->nullable();
        $table->enum('status', ['pending','bought','skipped'])->default('pending');
        $table->timestamps();


        $table->unique(['shopping_list_id','ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_lists_items');
    }
};

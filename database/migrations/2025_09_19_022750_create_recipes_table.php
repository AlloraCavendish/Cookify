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
        Schema::create('recipes', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->text('steps')->nullable();
        $table->integer('cooking_time')->nullable()->comment('minutes');
        $table->string('difficulty')->nullable();
        $table->string('cuisine')->nullable();
        $table->text('instructions')->nullable();
        $table->string('image')->nullable();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // admin/user who created
        $table->timestamps();        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

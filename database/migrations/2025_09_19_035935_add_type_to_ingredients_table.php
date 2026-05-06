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
        Schema::table('ingredients', function ($table) {
            $table->string('type')->default('main'); // default is main
        });
    }

    public function down(): void
    {
        Schema::table('ingredients', function ($table) {
            $table->dropColumn('type');
        });
    }

};

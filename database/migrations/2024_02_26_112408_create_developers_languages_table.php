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
        Schema::create('developers_languages', function (Blueprint $table) {
            $table->foreignId('dev_id')->constrained('developers', 'id');
            $table->foreignId('language_id')->constrained('programming_languages');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developers_languages');
    }
};

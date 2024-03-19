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
        Schema::create('job_languages', function (Blueprint $table) {
            $table->foreignId('job_offer_id')->constrained('job_offers');
            $table->foreignId('programming_language_id')->constrained('programming_languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_languages');
    }
};

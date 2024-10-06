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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->foreignId('job_id')->constrained('job_offers')->onDelete('cascade');
            $table->foreignId('developer_id')->constrained('developers')->onDelete('cascade');
            $table->string('cv');
            $table->string('cover_letter');
            $table->enum('status', ['accepted', 'rejected', 'pending'])->default('pending'); // État de la candidature
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->boolean('is_validated')->default(0);
            $table->foreignId('contract_id')->constrained('types_contracts');
            $table->foreignId('year_id')->constrained('years_experiences');
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('type_id')->constrained('types_developers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};

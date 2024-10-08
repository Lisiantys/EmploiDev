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
        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->string('profil_image')->nullable();
            $table->string('first_name', 30);
            $table->string('surname', 30);
            $table->string('cv');
            $table->string('cover_letter');
            $table->text('description')->nullable();
            $table->boolean('is_free')->default(1);
            $table->boolean('is_validated')->default(0);
            $table->foreignId('contract_id')->constrained('types_contracts');
            $table->foreignId('year_id')->constrained('years_experiences');
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('type_id')->constrained('types_developers');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};

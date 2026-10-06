<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pairs of people an admin has said are not the same person, so the
     * duplicate finder stops suggesting them. Each pair is stored once, with
     * the lower id first.
     */
    public function up(): void
    {
        Schema::create('person_non_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->foreignId('other_person_id')->constrained('people')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['person_id', 'other_person_id']);
            $table->index('other_person_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_non_matches');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bridge the existing invites and submission applications to the 3.0
     * people/exhibitors tables. Additive only: invites is kept as-is.
     */
    public function up(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('show_id')->constrained('people')->nullOnDelete();
        });

        Schema::table('submission_applications', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->after('show_id')->constrained('people')->nullOnDelete();
        });

        Schema::table('exhibitors', function (Blueprint $table) {
            $table->foreignId('submission_application_id')->nullable()->after('show_id')->constrained('submission_applications')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exhibitors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('submission_application_id');
        });

        Schema::table('submission_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('person_id');
        });

        Schema::table('invites', function (Blueprint $table) {
            $table->dropConstrainedForeignId('person_id');
        });
    }
};

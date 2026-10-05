<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Give each application a secret key so applicants can view their
     * application without exposing everyone's contact details by ID.
     */
    public function up(): void
    {
        Schema::table('submission_applications', function (Blueprint $table) {
            $table->uuid('key')->nullable()->after('id');
        });

        DB::table('submission_applications')->whereNull('key')->orderBy('id')->each(function ($application) {
            DB::table('submission_applications')
                ->where('id', $application->id)
                ->update(['key' => (string) Str::uuid()]);
        });

        Schema::table('submission_applications', function (Blueprint $table) {
            $table->unique('key');
        });
    }

    public function down(): void
    {
        Schema::table('submission_applications', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->dropColumn('key');
        });
    }
};

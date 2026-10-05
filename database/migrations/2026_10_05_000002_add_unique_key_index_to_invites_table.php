<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Every RSVP link looks invites up by key; index it and keep keys unique.
     */
    public function up(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->unique('key');
        });
    }

    public function down(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropUnique(['key']);
        });
    }
};

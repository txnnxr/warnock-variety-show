<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->timestamp('waitlisted_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('nudge_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn(['waitlisted_at', 'reminder_sent_at', 'nudge_sent_at']);
        });
    }
};

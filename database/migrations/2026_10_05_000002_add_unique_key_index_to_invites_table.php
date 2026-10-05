<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Every RSVP link looks invites up by key; index it and keep keys unique.
     */
    public function up(): void
    {
        // The old site copied invites to a new show without new keys, so some
        // keys appear on two rows. The original (lowest id) keeps its key and
        // each copy gets a fresh one. RSVP links include the show id, so only
        // links for the copied show stop working.
        $duplicates = DB::table('invites')
            ->select('key')
            ->whereNotNull('key')
            ->groupBy('key')
            ->havingRaw('count(*) > 1')
            ->pluck('key');

        foreach ($duplicates as $key) {
            DB::table('invites')
                ->where('key', $key)
                ->orderBy('id')
                ->pluck('id')
                ->slice(1)
                ->each(fn ($id) => DB::table('invites')->where('id', $id)->update(['key' => (string) Str::uuid()]));
        }

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

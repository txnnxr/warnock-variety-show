<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * A secret per-show link for guest requests. RSVPs through it are
     * approved automatically; RSVPs from the public show page still need
     * approval.
     */
    public function up(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->uuid('guest_link_key')->nullable()->after('id');
        });

        DB::table('shows')->whereNull('guest_link_key')->orderBy('id')->each(function ($show) {
            DB::table('shows')->where('id', $show->id)->update(['guest_link_key' => (string) Str::uuid()]);
        });

        Schema::table('shows', function (Blueprint $table) {
            $table->unique('guest_link_key');
        });
    }

    public function down(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropUnique(['guest_link_key']);
            $table->dropColumn('guest_link_key');
        });
    }
};

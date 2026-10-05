<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->boolean('count_performers')->default(false)->after('max_attendants');
        });
    }

    public function down(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropColumn('count_performers');
        });
    }
};

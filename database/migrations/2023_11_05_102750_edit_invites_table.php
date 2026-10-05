<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * These columns were originally added to the invites table by hand, so
     * create them when missing and only modify them when they already exist.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invites', function (Blueprint $table) {
            if (Schema::hasColumn('invites', 'has_plus_one_option')) {
                $table->boolean('has_plus_one_option')->default(1)->change();
            } else {
                $table->boolean('has_plus_one_option')->default(1);
            }

            if (! Schema::hasColumn('invites', 'plus_one_status')) {
                $table->boolean('plus_one_status')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invites', function (Blueprint $table) {
            $table->dropColumn(['has_plus_one_option', 'plus_one_status']);
        });
    }
};

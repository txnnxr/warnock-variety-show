<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvitesTableAddTextareaAndPlusOneCheckbox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invites', function($table)
        {
            $table->tinyText('talent_write_in')->nullable();
            $table->boolean('has_plus_one_option')->default(false);
            $table->boolean('plus_one_status')->default(false);
            $table->tinyText('plus_one_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invites', function($table)
        {
            $table->dropColumn('talent_write_in');
            $table->dropColumn('has_plus_one_option');
            $table->dropColumn('plus_one_status');
            $table->dropColumn('plus_one_name');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvitesTableResponseStatusDefault extends Migration
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
            $table->string('response_status', 50)->default('CREATED')->change();
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
            $table->string('response_status', 50)->default('PENDING')->change();
        });
    }
}

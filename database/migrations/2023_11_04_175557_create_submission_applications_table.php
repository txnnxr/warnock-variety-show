<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows');
            $table->string('name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submission_applications');
    }
};

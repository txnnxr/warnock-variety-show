<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_info')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // The shows table already exists (2022_04_26_222810_create_shows_table).
        Schema::table('shows', function (Blueprint $table) {
            $table->boolean('canceled')->default(false);
        });

        Schema::create('exhibitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people', 'id')->onDelete('cascade');
            $table->foreignId('show_id')->constrained('shows', 'id')->onDelete('cascade');
            $table->text('exhibition_description');
            $table->enum('status', ['Pending', 'Approved', 'Denied']);
            $table->integer('performance_order');
            $table->boolean('plus_one')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people', 'id')->onDelete('cascade');
            $table->foreignId('show_id')->constrained('shows', 'id')->onDelete('cascade');
            $table->boolean('plus_one')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('guests');
        Schema::dropIfExists('exhibitors');
        Schema::dropIfExists('people');

        Schema::table('shows', function (Blueprint $table) {
            $table->dropColumn('canceled');
        });
    }
};

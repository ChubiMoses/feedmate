<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('auth_id');
            $table->integer('school_id');
            $table->integer('course_id');
            $table->integer('phone_number')->nullable();
            $table->string('about');
            $table->string('token');
            $table->string('email')->nullable();
            $table->string('profile_picture')->nullable();
            $table->integer('blocked');
            $table->integer('deactivated');
            $table->integer('subscribed');
            $table->integer('rating');
            $table->integer('points');
            $table->timestamp('sub_date');
            $table->timestamp('last_visit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

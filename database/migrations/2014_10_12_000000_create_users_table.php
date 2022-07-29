<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->text('avatar')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->integer('age')->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('gender')->nullable();
            $table->text('about_me')->nullable();
            $table->string('language')->nullable();
            $table->float('rating')->default(0);
            $table->decimal('wallet')->default(0);
            $table->unsignedBigInteger('provider_id')->unique();
            $table->integer('read_school_exp')->default(0);
            $table->boolean('is_vip')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
}

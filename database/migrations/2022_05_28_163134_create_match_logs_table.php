<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_1')
                ->constrained('users');
            $table->foreignId('user_2')
                ->constrained('users');
            $table->tinyInteger('user_1_rating')->default(-1);
            $table->tinyInteger('user_2_rating')->default(-1);
            $table->boolean('is_match')->nullable();
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
        Schema::dropIfExists('match_logs');
    }
}

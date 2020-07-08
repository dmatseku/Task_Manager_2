<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name')->default('Name');
            $table->date('begin_in');
            $table->date('finish_in');
            $table->enum('status', ['created', 'began', 'finished'])->default('created');
            $table->enum('type', ['Development', 'Bug fix', 'Design', 'Other'])->default('Development');
            $table->string('description', 1024)->nullable()->default('');
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
        Schema::dropIfExists('tasks');
    }
}

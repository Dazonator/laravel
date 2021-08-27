<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('add_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('text');
            $table->string('performers');
            $table->integer('initiator');
            $table->string('priority');
            $table->date('deadline');
            $table->date('startdate');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('add_tasks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
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
            $table->string('title');
            $table->text('text')->nullable();
            $table->json('performers_id')->nullable();
            $table->integer('initiator_id')->nullable();
            $table->integer('priority_id')->nullable();
            $table->date('deadline')->nullable();
            $table->date('startdate')->nullable();
            $table->integer('status_id')->default(1);
            $table->integer('parent_id')->nullable();
            $table->integer('in_step')->nullable();
            $table->integer('initial_department',)->nullable();
            $table->integer('distribution_department',)->nullable();
            $table->boolean('is_distributed',)->nullable();
            $table->integer('meeting_id',)->nullable();
            $table->integer('structure_id',)->nullable();
            $table->integer('creator_id')->nullable();
            $table->softDeletes();
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

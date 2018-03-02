<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardsTable extends Migration
{
    protected $name = 'boards';

    public function up()
    {
        if (!Schema::hasTable($this->name)) {
            Schema::create($this->name, function (Blueprint $table) {
                $table->increments('id');
                $table->string('title', 128);
                $table->string('description', 255)->nullable();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
                // Constraints declaration
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::drop('boards');
    }
}

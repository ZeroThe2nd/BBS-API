<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{

    protected $name = 'posts';

    public function up()
    {
        if (!Schema::hasTable($this->name)) {
            Schema::create($this->name, function (Blueprint $table) {
                $table->increments('id');
                $table->text('content');
                $table->integer('thread_id')->unsigned();
                $table->foreign('thread_id')->references('id')->on('threads');
                $table->integer('created_by')->unsigned();
                $table->foreign('created_by')->references('id')->on('users');
                $table->integer('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users');
                $table->integer('deleted_by')->unsigned()->nullable();
                $table->foreign('deleted_by')->references('id')->on('users');
                // Constraints declaration
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::drop('posts');
    }
}

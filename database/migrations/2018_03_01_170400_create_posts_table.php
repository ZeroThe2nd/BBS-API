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
        Schema::drop('posts');
    }
}

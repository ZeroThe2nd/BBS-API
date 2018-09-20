<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    protected $name = 'users';

    public function up()
    {
        if (!Schema::hasTable($this->name)) {
            Schema::create($this->name, function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 64)->unique();
                $table->string('password', 255);
                $table->string('avatar', 255)->nullable();
                $table->string('api_token', 64)->unique()->nullable();
                $table->boolean('is_admin')->default(false);
                $table->integer('updated_by')->unsigned()->nullable();
                $table->foreign('updated_by')->references('id')->on('users');
                $table->integer('deleted_by')->unsigned()->nullable();
                $table->foreign('deleted_by')->references('id')->on('users');
                // Constraints declaration
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::drop('users');
    }
}

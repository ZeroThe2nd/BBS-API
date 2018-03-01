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

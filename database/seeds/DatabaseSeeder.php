<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => "admin",
            'password' => "admin",
            'is_admin' => true,
        ]);

        $userId = (int)DB::getPdo()->lastInsertId();
        DB::table('boards')->insert([
            'title'       => "Default threads",
            'description' => "Automagically created test threads.",
            'user_id'     => $userId,
        ]);

        $boardId = (int)DB::getPdo()->lastInsertId();
        DB::table('threads')->insert([
            'title'    => "This is a test thread.",
            'board_id' => $boardId,
            'user_id'  => $userId,
        ]);

        $threadId = (int)DB::getPdo()->lastInsertId();
        DB::table('posts')->insert([
            'content'   => "This is a test post.",
            'thread_id' => $threadId,
            'user_id'   => $userId,
        ]);
    }
}

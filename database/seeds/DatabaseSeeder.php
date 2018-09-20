<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use \App\Traits\TokenGenerator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username'   => "admin",
            'password'   => Hash::make('admin'),
            'api_token'  => $this->generateTokenForApi(),
            'is_admin'   => true,
            'created_at' => Carbon::now()->getTimestamp(),
        ]);

        $userId = (int)DB::getPdo()->lastInsertId();
        DB::table('boards')->insert([
            'title'       => "Default threads",
            'description' => "Automagically created test board.",
            'created_by'  => $userId,
            'updated_by'  => $userId,
            'created_at'  => Carbon::now()->getTimestamp(),
        ]);

        DB::table('threads')->insert([
            'title'      => "Automagically created test thread.",
            'board_id'   => (int)DB::getPdo()->lastInsertId(),
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => Carbon::now()->getTimestamp(),
        ]);

        DB::table('posts')->insert([
            'content'    => "You're probably bored of this, but this is an automagically created test post. Admin, plz fix.",
            'thread_id'  => (int)DB::getPdo()->lastInsertId(),
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => Carbon::now()->getTimestamp(),
        ]);
    }
}

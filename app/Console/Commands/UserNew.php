<?php

namespace App\Console\Commands;

use App\Traits\TokenGenerator;
use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserNew extends Command
{
    use TokenGenerator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:new
    {--admin : Pass this if the new user should be an admin.}
    {username? : Sets the username of the new user.}
    {password? : Directly assign a password to the new user.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user via commandline';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');
        $apiToken = $this->generateTokenForApi();
        $isAdmin  = ($this->option('admin')) ? true : false;
        $userType = ($isAdmin) ? 'admin' : 'user';

        if (is_null($username)) {
            $username = $this->ask("Enter a username for the new $userType");
        }

        if (is_null($password)) {
            $password = $this->secret('Please enter a password for the new user');
        }

        $data = [
            'username'   => $username,
            'password'   => $password,
            'api_token'  => $apiToken,
            'is_admin'   => $isAdmin,
            'created_at' => Carbon::now()->getTimestamp(),
        ];

        $validator = Validator::make($data, User::$rules);

        if ($validator->fails()) {
            $this->error("Validation failed.");
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }
            $this->error("New user NOT created.");

            return false;
        }

        try {
            $user            = new User();
            $user->username  = $username;
            $user->password  = Hash::make($password);
            $user->api_token = $apiToken;
            $user->is_admin  = $isAdmin;
            $user->saveOrFail();
        } catch (\Throwable $e) {
            $this->error('User not created due to internal error.');
            $this->error($e->getMessage());
        }

        $this->info("New $userType $username created.");
        $this->info("API token set to: \"$user->api_token\"");

        return true;
    }
}

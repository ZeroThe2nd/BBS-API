<?php
/**
 * Created by PhpStorm.
 * User: zerothe2nd
 * Date: 3/1/18
 * Time: 5:15 PM
 */

namespace App\Http\Controllers\v1;

use App\User;
use Illuminate\Http\Request;

/**
 * Trait CommonFunctions
 *
 * @package App\Http\Controllers\v1
 */
trait CommonFunctions
{
    private $inputKey = 'api_token';

    /**
     * Get the token for the current request.
     *
     * @param Request $request
     *
     * @return string
     */
    public function getRequestToken(Request $request)
    {
        $token = $request->query($this->inputKey);

        if (empty($token)) {
            $token = $request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $request->bearerToken();
        }

        if (empty($token)) {
            $token = $request->getPassword();
        }

        return $token;
    }

    /**
     * @param Request $request
     *
     * @return User|null
     */
    public function currentUser(Request $request)
    {
        if (!is_null($request->getUser()) && !is_null($request->getPassword())) {
            /**
             * Get userdata by user-pass combo
             *
             * @var \App\User $user
             */
            $user = User::query()->where('username', $request->getUser())->first();
            if (!is_null($user)) {
                $user->makeVisible('password');
                if ((Hash::check($request->getPassword(), $user->password)) ||
                    ($request->getPassword() === $user->password)) {
                    return $user;
                }
            }
        }

        if (!is_null($request->getPassword())) {
            $user = User::query()->where('api_token', $this->getRequestToken($request))->first();

            return $user;
        }

        return $user = null;
    }
}

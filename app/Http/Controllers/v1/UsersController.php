<?php namespace App\Http\Controllers\v1;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\v1
 */
class UsersController extends Controller
{
    const MODEL = "App\User";
    use RESTActions;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $username = $request->get('username');
        if (User::query()->where(['username' => $username,])->exists()) {
            return $this->respond(Response::HTTP_NOT_ACCEPTABLE, [
                'error'   => true,
                'message' => 'The selected username is taken',
            ]);
        }
        $password  = Hash::make($request->get('password'));
        $api_token = $this->generateApiToken();

        $user            = new User;
        $user->username  = $username;
        $user->password  = $password;
        $user->api_token = $api_token;

        if (!$user->save()) {
            return $this->respond(Response::HTTP_NOT_ACCEPTABLE, [
                'error'   => true,
                'message' => 'An unknown error occurred',
            ]);
        }

        return $this->respond(Response::HTTP_CREATED, $user->makeVisible($user->getHidden()));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrent(Request $request)
    {
        $user = User::query()->where([
            'api_token' => $this->getRequestToken($request),
        ])->first();

        if (!is_null($user)) {
            return response()->json($user, Response::HTTP_OK);
        }

        return response()->json([
            "error"   => true,
            "message" => "Couldn't retrieve your data. Is your api_token still valid?",
        ]);
    }

    /**
     * Get user token bu username and password
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken(Request $request)
    {
        /**
         * @var \App\User $user
         */
        $user = User::query()->where('username', $request->getUser())->firstOrFail();
        $raw  = false;
        if (!is_null($user)) {
            $user->makeVisible($user->getHidden());
            if (Hash::check($request->getPassword(), $user->password) ||
                ($raw = $request->getPassword() === $user->password)) {
                $data['api_token'] = $user->api_token;
                if ($raw) {
                    $data['message'] = 'For security reasons, please update your password';
                }

                return response()->json($data);
            }
        }

        return response()->json(
            [
                'error'   => true,
                'message' => "Couldn't find a matching user.",
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Generate a new user token if username and password match
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateToken(Request $request)
    {
        /** @var \App\User $user */
        $user = User::query()->where('username', $request->getUser())->firstOrFail();
        $raw  = false;
        if (!is_null($user)) {
            $user->makeVisible($user->getHidden());
            if ((Hash::check($request->getPassword(), $user->password)) ||
                ($raw = ($request->getPassword() === $user->password))) {
                $user->api_token = $this->generateApiToken();
                $user->save();

                $data['api_token'] = $user->api_token;
                $data['message'][] = 'New API token generated. Your old token is now invalid.';
                $data['message'][] .= ($raw) ? 'For security reasons, please update your password.' : '';

                return response()->json($data);
            }
        }

        return response()->json(
            [
                'error'   => true,
                'message' => "Couldn't find a matching user.",
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Generates a unique API key that does not exist in the database yet.
     * True on success, null on failure
     *
     * @return null|string
     */
    private function generateApiToken()
    {
        $exists = false;
        $token  = null;
        while (!$exists) {
            $token = $this->generateToken(64);
            if (is_null($token)) {
                // Token generator failed
                return null;
            }
            $exists = User::query()->where([
                'api_token' => $token,
            ])->exists();
            if (!$exists) {
                return $token;
            }
        }

        // Couldn't generate an unused api_token
        return null;
    }

    /**
     * Generates a cryptographically safe random string with a given length
     * Returns `null` on error
     *
     * @param int $length
     *
     * @return string|null
     */
    private function generateToken($length = 64)
    {
        $token        = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max          = strlen($codeAlphabet);

        try {
            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[random_int(0, $max - 1)];
            }
        } catch (\Exception $e) {
            echo '<pre>' . print_r($e->getMessage(), true) . '</pre>';

            return null;
        }

        return $token;
    }
}

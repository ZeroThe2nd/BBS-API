<?php namespace App\Http\Controllers\v1;

use App\User;
use Illuminate\Http\Request;
use App\Traits\TokenGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\v1
 */
class UsersController extends Controller
{
    const MODEL = "App\User";

    use RESTActions;
    use TokenGenerator;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $username = $request->json()->get('username');
        if (User::query()->where(['username' => $username,])->exists()) {
            return $this->respond(JsonResponse::HTTP_NOT_ACCEPTABLE, [
                'error'   => true,
                'message' => 'The selected username is taken',
            ]);
        }
        $password  = Hash::make($request->json()->get('password'));
        $api_token = $this->generateTokenForApi();

        $user            = new User;
        $user->username  = $username;
        $user->password  = $password;
        $user->api_token = $api_token;

        if (!$user->save()) {
            return $this->respond(JsonResponse::HTTP_NOT_ACCEPTABLE, [
                'error'   => true,
                'message' => 'An unknown error occurred',
            ]);
        }

        return $this->respond(JsonResponse::HTTP_CREATED, $user->makeVisible($user->getHidden()));
    }

    public function put(Request $request, $id)
    {
        $user = User::query()->find($id);
        $user->makeVisible("password");
        $currUser = $this->currentUser($request);

        if (is_null($user)) {
            return response()->json(JsonResponse::HTTP_NOT_FOUND);
        }

        if (!((bool)$currUser->is_admin) && !((bool)($user instanceof User && $user->id === $currUser->id))) {
            return response()->json([
                'error'   => true,
                'message' => "You're not allowed to change this item",
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = array_merge($user->toArray(), $request->all());

        if (!is_null($newPassword = $request->get('password'))) {
            $data['password'] = Hash::make($newPassword);
        }

        $request->replace($data);

        try {
            $this->validate($request, User::$rules);
        } catch (ValidationException $e) {
            return response()->json('Failed validation', JsonResponse::HTTP_BAD_REQUEST);
        }

        $user->update($request->all());

        return $this->respond(JsonResponse::HTTP_OK, $user->makeVisible('updated_at'));
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
        ])->first()->makeVisible([
            'updated_at',
            'api_token',
        ]);

        if (!is_null($user)) {
            return response()->json($user, JsonResponse::HTTP_OK);
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
            if (Hash::check($request->getPassword(),
                    $user->password) || ($raw = $request->getPassword() === $user->password)) {
                $data['api_token'] = $user->api_token;
                if ($raw) {
                    $data['message'] = 'For security reasons, please update your password';
                }

                return response()->json($data);
            }
        }

        return response()->json([
            'error'   => true,
            'message' => "Couldn't find a matching user.",
        ], JsonResponse::HTTP_UNAUTHORIZED);
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
            if ((Hash::check($request->getPassword(),
                    $user->password)) || ($raw = ($request->getPassword() === $user->password))) {
                $user->api_token = $this->generateTokenForApi();
                $user->save();

                $data['api_token'] = $user->api_token;
                $data['message'][] = 'New API token generated. Your old token is now invalid.';
                $data['message'][] .= ($raw) ? 'For security reasons, please update your password.' : '';

                return response()->json($data);
            }
        }

        return response()->json([
            'error'   => true,
            'message' => "Couldn't find the user.",
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}

<?php namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Trait RESTActions
 *
 * @package App\Http\Controllers
 */
trait RESTActions
{

    use CommonFunctions;

    /**
     * @param bool $withRelations
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all($withRelations = false)
    {
        $m = self::MODEL;

        $data = $m::all();

        return $this->respond(Response::HTTP_OK, $data);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $m     = self::MODEL;
        $model = $m::find($id)->with([
            'created_by',
            'updated_by',
        ]);
        if (is_null($model)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $model);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $m = self::MODEL;

        $user = $this->currentUser($request);

        if (!is_null($user)) {
            // Add or override the user_id with the currently logged in user
            $request->merge([
                'created_by' => (int)$user->id,
                'updated_by' => (int)$user->id,
            ]);
        } else {
            throw new \Exception('User object empty');
            // Forcibly remove the user_id if the user is not set
            // FIXME WHY?!

            // $data = $request->all();
            // unset($data['user_id']);
            // $request->replace($data);
        }

        $this->validate($request, $m::$rules);

        return $this->respond(Response::HTTP_CREATED, $m::create($request->all()));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function put(Request $request, $id)
    {
        $m     = self::MODEL;
        $model = $m::find($id);
        $user  = $this->currentUser($request);

        if (is_null($model)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        $currUserIsOwner = (bool)(isset($model->created_by) && (int)$model->created_by === (int)$user->id);
        $currUserIsAdmin = (bool)$user->is_admin;
        $currUserIsUser  = (bool)($m === "App\User" && $model->id === $user->id); // TODO Check if this still works

        if (!$currUserIsOwner && !$currUserIsAdmin && !$currUserIsUser) {
            return response()->json([
                'error'   => true,
                'message' => "You're not allowed to change this item",
            ], Response::HTTP_UNAUTHORIZED);
        }

        //        echo '<pre>' . print_r($model->toArray(), true) . '</pre>';die();

        $request->replace(array_merge($model->toArray(), // Get current model
                $request->all(), // Merge request data into the model
                [

                    'updated_by' => $user->id,
                ] // Add the current user id as updated_by
            ));

        $this->validate($request, $m::$rules);
        $model->update($request->all());

        return $this->respond(Response::HTTP_OK, $model);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($id)
    {
        $m = self::MODEL;
        if (is_null($m::find($id))) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }
        $m::destroy($id);

        return $this->respond(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param       $status
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($status, $data = [])
    {
        return response()->json($data, $status);
    }
}

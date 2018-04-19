<?php namespace App\Http\Controllers\v1;

use App\Thread;
use Illuminate\Http\Response;

/**
 * Class ThreadsController
 *
 * @package App\Http\Controllers\v1
 */
class ThreadsController extends Controller
{
    const MODEL = "App\Thread";
    use RESTActions;

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $thread = Thread::query()->with([
            'user',
            'threads',
            'posts',
        ])->find($id);

        if (is_null($thread)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $thread);
    }
}

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

    public function all()
    {
        $threads = Thread::query()->with(['created_by', 'updated_by', 'board'])->get();

        if (is_null($threads)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $threads);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $thread = Thread::query()->with([
            'created_by',
            'updated_by',
            'posts',
        ])->find($id);

        if (is_null($thread)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $thread);
    }
}

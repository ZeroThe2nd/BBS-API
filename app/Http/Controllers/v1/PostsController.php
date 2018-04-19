<?php namespace App\Http\Controllers\v1;

use App\Post;
use Illuminate\Http\Response;

/**
 * Class PostsController
 *
 * @package App\Http\Controllers\v1
 */
class PostsController extends Controller
{
    const MODEL = "App\Post";
    use RESTActions;

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $post = Post::query()->with([
            'user',
            'thread' => function ($query) {
                $query->with('threads');
            },
        ])->find($id);

        if (is_null($post)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $post);
    }
}

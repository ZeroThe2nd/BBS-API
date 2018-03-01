<?php namespace App\Http\Controllers\v1;

use App\Board;
use Illuminate\Http\Response;

/**
 * Class BoardsController
 *
 * @package App\Http\Controllers\v1
 */
class BoardsController extends Controller
{
    const MODEL = "App\Board";
    use RESTActions;

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $board = Board::query()->with(['user'])->find($id);

        if (is_null($board)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $board);
    }
}

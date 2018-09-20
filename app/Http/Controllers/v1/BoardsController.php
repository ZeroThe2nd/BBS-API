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

    public function all()
    {
        $boards = Board::query()->with(['created_by', 'updated_by', 'threads'])->get();

        if (is_null($boards)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $boards);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $board = Board::query()->with(['created_by', 'updated_by', 'threads'])->find($id);

        if (is_null($board)) {
            return $this->respond(Response::HTTP_NOT_FOUND);
        }

        return $this->respond(Response::HTTP_OK, $board);
    }
}

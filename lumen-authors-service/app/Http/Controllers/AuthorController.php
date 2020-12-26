<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\AuthorRequest;
use App\Traits\AppResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    use AppResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $authors = Author::all();

        return $this->successResponse($authors);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'name'    => 'required|max:32',
            'gender'  => 'required|max:8|in:male,female',
            'country' => 'required|max:16',
        ];
        $this->validate($request, $rules);
        $author = $this->author->create($request->all(), $rules);

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * @param int $author
     * @return JsonResponse
     */
    public function show(int $author): JsonResponse
    {
        $author = Author::findOrFail($author);

        return $this->successResponse($author);
    }

    /**
     * @param Request $request
     * @param int     $author
     * @return JsonResponse
     */
    public function update(Request $request, int $author): JsonResponse
    {
        $rules = [
            'name'    => 'max:32',
            'gender'  => 'max:8|in:male,female',
            'country' => 'max:16',
        ];
        $this->validate($request, $rules);

        $author = Author::findOrFail($author);
        $author->fill($request->all());
        if ($author->isClean())
            return $this->errorResponse('At least one attribute must be provided', Response::HTTP_UNPROCESSABLE_ENTITY);
        $author->save();

        return $this->successResponse($author);
    }

    /**
     * @param int $author
     * @return JsonResponse
     */
    public function destroy(int $author): JsonResponse
    {
        $author = Author::findOrFail($author);
        $author->delete();

        return $this->successResponse($author);
    }
}

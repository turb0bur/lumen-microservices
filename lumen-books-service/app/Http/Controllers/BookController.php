<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\AppResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use AppResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $books = Book::all();

        return $this->successResponse($books);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'title'       => 'required|max:32',
            'description' => 'required|max:255',
            'price'       => 'required|numeric|min:0',
            'author_id'   => 'required|integer|min:1',
        ];
        $this->validate($request, $rules);
        $book = $this->book->create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * @param int $book
     * @return JsonResponse
     */
    public function show(int $book): JsonResponse
    {
        $book = Book::findOrFail($book);

        return $this->successResponse($book);
    }

    /**
     * @param Request $request
     * @param int     $book
     * @return JsonResponse
     */
    public function update(Request $request, int $book): JsonResponse
    {
        $rules = [
            'title'       => 'max:32',
            'description' => 'max:255',
            'price'       => 'numeric|min:0',
            'author_id'   => 'integer|min:1',
        ];
        $this->validate($request, $rules);

        $book = Book::findOrFail($book);
        $book->fill($request->all());
        if ($book->isClean())
            return $this->errorResponse('At least one attribute must be provided', Response::HTTP_UNPROCESSABLE_ENTITY);
        $book->save();

        return $this->successResponse($book);
    }

    /**
     * @param int $book
     * @return JsonResponse
     */
    public function destroy(int $book): JsonResponse
    {
        $book = Book::findOrFail($book);
        $book->delete();

        return $this->successResponse($book);
    }
}

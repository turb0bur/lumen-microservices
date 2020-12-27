<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\AuthorService;
use App\Services\BookService;
use App\Traits\AppResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class BookController
 *
 * @package App\Http\Controllers
 */
class BookController extends Controller
{
    use AppResponder;


    public $bookService;
    public $authorService;

    /**
     * Create a new controller instance.
     *
     * @param BookService   $bookService
     * @param AuthorService $authorService
     */
    public function __construct(BookService $bookService, AuthorService $authorService)
    {
        $this->bookService   = $bookService;
        $this->authorService = $authorService;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $books = $this->bookService->obtainBooks();

        return $this->successResponse($books);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $author = $this->authorService->obtainAuthor($request->author_id);
        $book   = $this->bookService->createBook($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * @param int $book
     * @return Response
     */
    public function show(int $book): Response
    {
        $book = $this->bookService->obtainBook($book);

        return $this->successResponse($book);
    }

    /**
     * @param Request $request
     * @param int     $book
     * @return Response
     */
    public function update(Request $request, int $book): Response
    {
        $book = $this->bookService->editBook($book, $request->all());

        return $this->successResponse($book);
    }

    /**
     * @param int $book
     * @return Response
     */
    public function destroy(int $book): Response
    {
        $book = $this->bookService->deleteBook($book);

        return $this->successResponse($book);
    }
}

<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\AuthorRequest;
use App\Services\AuthorService;
use App\Traits\AppResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AuthorController
 *
 * @package App\Http\Controllers
 */
class AuthorController extends Controller
{
    use AppResponder;

    public $authorService;

    /**
     * Create a new controller instance.
     *
     * @param AuthorService $authorService
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $authors = $this->authorService->obtainAuthors();

        return $this->successResponse($authors);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $author = $this->authorService->createAuthor($request->all());

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * @param int $author
     * @return Response
     */
    public function show(int $author): Response
    {
        $author = $this->authorService->obtainAuthor($author);

        return $this->successResponse($author);
    }

    /**
     * @param Request $request
     * @param int     $author
     * @return Response
     */
    public function update(Request $request, int $author): Response
    {
        $author = $this->authorService->editAuthor($author, $request->all());

        return $this->successResponse($author);
    }

    /**
     * @param int $author
     * @return Response
     */
    public function destroy(int $author): Response
    {
        $author = $this->authorService->deleteAuthor($author);

        return $this->successResponse($author);
    }
}

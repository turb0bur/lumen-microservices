<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;

/**
 * Class BookService
 *
 * @package App\Services
 */
class BookService
{
    use ConsumeExternalServices;

    public $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.books.base_uri');
    }

    /**
     * Obtain the full list of books from the Books service
     *
     * @return string
     */
    public function obtainBooks(): string
    {
        return $this->performRequest('GET', '/books');
    }

    /**
     * Create an book using the Books service
     *
     * @param $data
     * @return string
     */
    public function createBook($data): string
    {
        return $this->performRequest('POST', '/books', $data);
    }

    /**
     * Obtain one single book from the Books service
     *
     * @param int $book
     * @return string
     */
    public function obtainBook(int $book): string
    {
        return $this->performRequest('GET', "/books/{$book}");
    }

    /**
     * Update the book using the Books service
     *
     * @param int   $book
     * @param array $data
     * @return string
     */
    public function editBook(int $book, array $data): string
    {
        return $this->performRequest('PATCH', "/books/{$book}", $data);
    }

    /**
     * Remove the single book using the Books service
     *
     * @param int $book
     * @return string
     */
    public function deleteBook(int $book): string
    {
        return $this->performRequest('DELETE', "/books/{$book}");
    }
}

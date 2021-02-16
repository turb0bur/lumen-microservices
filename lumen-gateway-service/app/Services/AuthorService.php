<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;

/**
 * Class AuthorService
 *
 * @package App\Services
 */
class AuthorService
{
    use ConsumeExternalServices;

    public $baseUri;

    public $secret;

    public function __construct()
    {
        $this->baseUri = config('services.authors.base_uri');
        $this->secret  = config('services.authors.secret');
    }

    /**
     * Obtain the full list of authors from the Authors service
     *
     * @return string
     */
    public function obtainAuthors(): string
    {
        return $this->performRequest('GET', '/authors');
    }

    /**
     * Create an author using the Authors service
     *
     * @param $data
     * @return string
     */
    public function createAuthor($data): string
    {
        return $this->performRequest('POST', '/authors', $data);
    }

    /**
     * Obtain one single author from the Authors service
     *
     * @param int $author
     * @return string
     */
    public function obtainAuthor(int $author): string
    {
        return $this->performRequest('GET', "/authors/{$author}");
    }

    /**
     * Update the author using the Authors service
     *
     * @param int   $author
     * @param array $data
     * @return string
     */
    public function editAuthor(int $author, array $data): string
    {
        return $this->performRequest('PATCH', "/authors/{$author}", $data);
    }

    /**
     * Remove the single author using the Authors service
     *
     * @param int $author
     * @return string
     */
    public function deleteAuthor(int $author): string
    {
        return $this->performRequest('DELETE', "/authors/{$author}");
    }
}

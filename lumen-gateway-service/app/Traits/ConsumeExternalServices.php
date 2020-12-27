<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumeExternalServices
{
    /**
     * Send a request to any service
     */
    public function performRequest($method, $requestUrl, $formParams = [], $headers = []): string
    {
        $client = new Client(['base_uri' => $this->baseUri]);

        $response = $client->request($method, $requestUrl, ['form_params' => $formParams, 'headers' => $headers]);

        return $response->getBody()->getContents();
    }
}

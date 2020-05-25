<?php

namespace App\Service\EvidentaPopulatiei;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SearchClient
 * @package App\Service\EvidentaPopulatiei
 */
class SearchClient
{
    const API_ENDPOINT = ''; // TODO

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * SearchClient constructor.
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @param string $cnp
     * @param string $firstName
     * @param string $lastName
     * @return string
     * @throws SearchClientException
     */
    public function getAddress(string $cnp, string $firstName, string $lastName): string
    {
        throw new SearchClientException('Evidenta Populatiei evidentaPopulatiei is not implemented!');
        $query = []; // TODO: build query parameters

        /** @var string $queryString */
        $queryString = http_build_query($query);

        try {
            /** @var ResponseInterface $response */
            $response = $this->getHttpClient()->get(self::API_ENDPOINT . '?' . $queryString);
        } catch (RequestException $requestException) {
            throw new SearchClientException(
                $requestException->getMessage(),
                $requestException->getCode(),
                $requestException
            );
        }

        /** @var string $responseBody */
        $responseBody = $response->getBody()->getContents();

        // TODO: implement logic @here
        return 'GetAddressFromResponse';
    }
}

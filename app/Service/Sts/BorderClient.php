<?php

namespace App\Service\Sts;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BorderClient
 * @package App\Sts
 */
class BorderClient
{
    const API_ENDPOINT = 'https://corona-forms.stsisp.ro/api/abroad-reports';

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * SmsClient constructor.
     *
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
     * @param string|null $documentNumber
     * @param string|null $documentSeries
     * @param string|null $cnp
     * @param DateTime|null $arrivedAfter
     * @param DateTime|null $arrivedBefore
     * @return string
     * @throws Exception
     */
    public function getDeclarations(
        string $documentNumber = null,
        string $documentSeries = null,
        string $cnp = null,
        DateTime $arrivedAfter = null,
        DateTime $arrivedBefore = null
    )
    {
        if (empty($documentNumber) && empty($cnp)) {
            throw new Exception('documentNumber of cpn are required');
        }

        $query = [];

        if (!empty($cnp)) {
            $query['filter']['person_cnp'] = trim($cnp);
        }

        if (!empty($documentNumber)) { // TODO: check this against STS documentation (not implemented yet on STS side)
            $query['filter']['person_document_number'] = trim($documentNumber);
        }

        if (!empty($documentSeries)) { // TODO: check this against STS documentation (not implemented yet on STS side)
            $query['filter']['person_document_series'] = trim($documentSeries);
        }

        if (!empty($arrivedAfter)) {
            $query['filter']['arrived_after'] = $arrivedAfter->format('Y-m-d H:i:s');
        }

        if (!empty($arrivedBefore)) {
            $query['filter']['arrived_before'] = $arrivedBefore->format('Y-m-d H:i:s');
        }

        /** @var string $queryString */
        $queryString = http_build_query($query);

        try {
            /** @var ResponseInterface $response */
            $response = $this->getHttpClient()->get(self::API_ENDPOINT . '?' . $queryString);
        } catch (RequestException $requestException) {
            throw new Exception(
                $requestException->getMessage(),
                $requestException->getCode(),
                $requestException
            );
        }

        $responseBody = $response->getBody()->getContents();

        if (empty($responseBody)) {
            throw new Exception('Empty response from STS');
        }

        return $responseBody;
    }
}

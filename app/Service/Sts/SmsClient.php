<?php

namespace App\Sts;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SmsClient
 * @package App\Sts
 */
class SmsClient
{
    const API_ENDPOINT = 'https://mc.stsisp.ro/webapi/sms/send/';

    const API_RESPONSES = [
        20 => 'message was accepted and delivered to a SMSC driver',
        21 => 'message was accepted and stored, temporarily no SMSC driver so it was queue',
        40 => 'message was not delivered',
        41 => 'telephone operator is unknown and not supported',
        42 => 'account message quota was reached',
        44 => 'message was not accepted for delivery, telephone number format is not accepted',
        50 => 'internal error sending message'
    ];

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * SmsClient constructor.
     *
     * @param Client $httpClient
     * @param string $username
     * @param string $password
     */
    public function __construct(Client $httpClient, string $username, string $password)
    {
        $this->httpClient = $httpClient;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return Client
     */
    private function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @return string
     */
    private function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    private function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $phoneNumber
     * @param string $message
     * @throws Exception
     */
    public function sendMessage(string $phoneNumber, string $message)
    {
        $params = [];
        $params['username'] = $this->getUsername();
        $params['password'] = $this->getPassword();
        $params['message'] = $message;
        $params['telephones'] = [$phoneNumber];

        try {
            /** @var ResponseInterface $response */
            $response = $this->getHttpClient()->post(
                self::API_ENDPOINT,
                [
                    RequestOptions::JSON => $params
                ]
            );
        } catch (RequestException $requestException) {
            throw new Exception(
                $requestException->getMessage(),
                $requestException->getCode(),
                $requestException
            );
        }

        $responseBody = $response->getBody()->getContents();

        if (empty($responseBody)) {
            throw new Exception('Empty response from SMS Gateway');
        }

        /** @var array $responseArray */
        $responseArray = json_decode($responseBody, true);

        if (empty($responseArray)) {
            throw new Exception('Invalid response from SMS Gateway');
        }

        if ('fail' == $responseArray['status']) {
            throw new Exception(
                $responseArray['message'],
                $responseArray['code']
            );
        }

        if (array_key_exists($phoneNumber, $responseArray['data'])) {
            if (!in_array($responseArray['data'][$phoneNumber], [20, 21])) {
                throw new Exception(self::API_RESPONSES[$responseArray['data'][$phoneNumber]]);
            }
        }
    }

    /**
     * @param string $phoneNumber
     * @return string
     */
    public function preparePhoneNumber(string $phoneNumber): string
    {
        /**
         * For Romania, remove the country code
         */
        if ('+40' == substr($phoneNumber, 0, 3)) {
            return str_replace('+40', '0', $phoneNumber);
        }

        /**
         * TODO: Ask STS about details (not covered by API documentation)
         */
        return $phoneNumber;
    }
}

<?php

namespace ShapLibrary;

use GuzzleHttp\Client;

/**
 * Class ShapLibrary
 *
 * @package ShapLibrary
 */
class ShapLibrary
{
    /**
     * Default SHAP server host
     */
    const SHAP_SERVER_HOST = 'http://shareandpull.lancaster-solutions.com/api/data/';

    /**
     * Max push into shap
     */
    const MAX_SHAP_PUSH = 1000;

    /**
     * Host for the server that we will support the SHAP calls
     *
     * @var string
     */
    private $serverHost;

    /**
     * CurlClient For Accessing SHAP
     *
     * @var Client
     */
    private $curlClient;

    /**
     * ApiKey to call a list and identify a Partner
     *
     * @var string
     */
    private $apiKey;

    /**
     * ShapLibrary constructor.
     *
     * ShapLibrary class. This class allow to use the resources of SHAP. On construct you need to provide the API key.
     * Each list in SHAP has a different key for each partner.
     *
     * @param      $apiKey
     * @param null $shapServerHost
     */
    public function __construct($apiKey, $shapServerHost = null)
    {
        $this->apiKey = $apiKey;
        $this->serverHost = ($shapServerHost) ? $shapServerHost : self::SHAP_SERVER_HOST;
        $this->curlClient = new Client(['base_uri' => $this->serverHost]);
    }

    /**
     * Return the list of dataTypes that a list has. So for example:
     *
     * GET /detail/
     *
     * [
     *      "listName" => 'Blacklisted',
     *      "listId" => 1,
     *      "ListDetails" => [
     *          "email"
     *      ]
     * ]
     *
     * @param bool $toArray
     *
     * @return mixed
     */
    public function detail($toArray = false)
    {
        $method = 'GET';
        $endpoint = 'detail';
        $response = $this->call($method, $endpoint);

        return $this->decodeResponse($response->getBody(), $toArray);
    }

    /**
     * Return the messages from the timestamp given up to the latest one. If return is > then 0 then you should
     * continue asking for messages until the request with the latest timestamp has 0 messages.
     *
     * @param      $timestamp
     *
     * @param bool $toArray
     *
     * @return mixed
     */
    public function pull($timestamp, $toArray = false)
    {
        $method = 'GET';
        $endpoint = 'pull/' . $timestamp;
        $response = $this->call($method, $endpoint);

        return $this->decodeResponse($response->getBody(), $toArray);
    }

    /**
     * Return the amount of elements that he was able to push to SHAP.
     *
     * @param array $messages
     *
     * @return int
     */
    public function push(array $messages)
    {
        $amountQueued = 0;

        $splitInput = $this->splitArray($messages);

        $method = 'POST';
        $endpoint = 'push';

        foreach ($splitInput as $item) {
            $response = $this->call($method, $endpoint, $item);
            $responseArray = $this->decodeResponse($response->getBody(), true);
            if (!empty($responseArray['queued'])) {
                $amountPushedMessages = $responseArray['queued'];
                $amountQueued += $amountPushedMessages;
            }
        }

        return $amountQueued;
    }

    /**
     * Set the ApiKey, you might want to change the key in the middle of some execution so you can
     * call another list. Also you don't need to create another client to call another list
     *
     * @param $apiKey
     *
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Call SHAP with the desired method
     *
     * @param $method
     * @param $endpoint
     * @param $body
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws ShapLibraryException
     */
    private function call($method, $endpoint, $body = null)
    {
        try {
            $response = $this->curlClient->request(
                $method,
                $endpoint,
                [
                    'headers'   => $this->getHeaders($body),
                    'body'      => json_encode($body)
                ]
            );

            return $response;
        } catch (\Exception $e) {
            throw new ShapLibraryException($e);
        }
    }

    /**
     * Get the response parsed and formatted, either STD object or array
     * depends on the parameter you send to the JSON decode function.
     *
     * @param      $response
     * @param bool $toArray
     *
     * @return mixed
     */
    private function decodeResponse($response, $toArray = false)
    {
        return json_decode($response, $toArray);
    }

    /**
     * Return a group of arrays from the original array with max shap push
     * as the divider of them.
     *
     * @param array $messages
     *
     * @return array
     */
    private function splitArray(array $messages)
    {
        return array_chunk($messages, self::MAX_SHAP_PUSH);
    }

    /**
     * Return the ApiKey array as a header
     *
     * @param $body
     *
     * @return array
     */
    private function getHeaders($body)
    {
        return [
                'partner-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Content-length' => strlen(json_encode($body)) ?: 0,
                'Accept' => 'application/json' ,
        ];
    }
}

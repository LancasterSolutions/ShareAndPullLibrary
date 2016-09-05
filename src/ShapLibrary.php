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
    const SHAP_SERVER_HOST = 'shareandpull.swarmiz.com';

    /**
     * Host for the server that we will support the SHAP calls
     *
     * @var string
     */
    protected $serverHost;

    /**
     * CurlClient For Accessing SHAP
     *
     * @var Client
     */
    protected $curlClient;

    /**
     * ShapLibrary constructor.
     *
     * ShapLibrary class. This class allow to use the resources of SHAP. On construct you need to provide the API key.
     * Each list in SHAP has a different key for each partner.
     *
     * Do not provide protocol nor final slash.
     *
     * @param      $apiKey
     * @param null $shapServerHost
     */
    public function __construct($apiKey, $shapServerHost = null)
    {
        $this->serverHost = ($shapServerHost) ? $shapServerHost : self::SHAP_SERVER_HOST;
        $this->curlClient = new Client(['base_uri' => $this->serverHost]);
    }
}

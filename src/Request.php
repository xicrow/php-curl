<?php
namespace Xicrow\PhpCurl;

use Xicrow\PhpCurl\Interfaces\RequestInterface;
use Xicrow\PhpCurl\Traits\CurlHandle;
use Xicrow\PhpCurl\Traits\CurlOptions;

/**
 * Class Request
 *
 * @package Xicrow\PhpCurl
 */
class Request implements RequestInterface
{
    use CurlHandle, CurlOptions;

    /**
     * Constructor
     *
     * @param array $curlOptions
     */
    public function __construct(array $curlOptions = [])
    {
        // Set cURL options
        $this->setCurlOptions($curlOptions);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        // Close handle
        $this->closeCurlHandle();
    }

    /**
     * Execute request
     *
     * @return Response
     */
    public function execute()
    {
        // Set options
        curl_setopt_array($this->getCurlHandle(), $this->getCurlOptions());

        // Get result
        $result = curl_exec($this->getCurlHandle());

        // Get information
        $info = curl_getinfo($this->getCurlHandle());

        // Return response
        return new Response($info, $result);
    }
}

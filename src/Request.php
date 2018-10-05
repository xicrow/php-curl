<?php
namespace Xicrow\PhpCurl;

use Xicrow\PhpCurl\Helpers\CurlOptions;
use Xicrow\PhpCurl\Interfaces\RequestInterface;

/**
 * Class Request
 *
 * @package Xicrow\PhpCurl
 */
class Request implements RequestInterface
{
    /**
     * cURL handle
     *
     * @var null|resource
     */
    private $curlHandle;

    /**
     * CurlOptions instance
     *
     * @var CurlOptions
     */
    private $curlOptions;

    /**
     * Constructor
     *
     * @param CurlOptions|array|null $curlOptions
     */
    public function __construct($curlOptions = null)
    {
        // Set CurlOptions instance
        $this->curlOptions = new CurlOptions();

        // Set cUrl options if given
        if ($curlOptions instanceof CurlOptions) {
            $this->curlOptions($curlOptions);
        } elseif (is_array($curlOptions)) {
            $this->curlOptions()->set($curlOptions);
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        // Close handle
        if (is_resource($this->curlHandle())) {
            curl_close($this->curlHandle());
        }
    }

    /**
     * Get/set CurlOptions instance
     *
     * @param resource|null $curlHandle
     *
     * @return resource
     */
    public function curlHandle($curlHandle = null)
    {
        if (!empty($curlHandle) && is_resource($curlHandle)) {
            $this->curlHandle = $curlHandle;
        }

        if (!is_resource($this->curlHandle)) {
            $this->curlHandle = curl_init();
        }

        return $this->curlHandle;
    }

    /**
     * Get/set CurlOptions instance
     *
     * @param CurlOptions|null $curlOptions
     *
     * @return CurlOptions
     */
    public function curlOptions(CurlOptions $curlOptions = null)
    {
        if (!empty($curlOptions)) {
            $this->curlOptions = $curlOptions;
        }

        return $this->curlOptions;
    }

    /**
     * Execute request
     *
     * @return Response
     */
    public function execute()
    {
        // Set options
        curl_setopt_array($this->curlHandle(), $this->curlOptions()->get());

        // Get result
        $result = curl_exec($this->curlHandle());

        // Get information
        $info = curl_getinfo($this->curlHandle());

        // Return response
        return new Response($info, $result);
    }
}

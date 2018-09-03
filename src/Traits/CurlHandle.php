<?php
namespace Xicrow\PhpCurl\Traits;

/**
 * Trait CurlHandle
 *
 * @package Xicrow\PhpCurl\Traits
 */
trait CurlHandle
{
    /**
     * cURL handle
     *
     * @var null|resource
     */
    private $curlHandle = null;

    /**
     * Get cURL handle
     *
     * @return null|resource
     */
    public function getCurlHandle()
    {
        if (!is_resource($this->curlHandle)) {
            $this->curlHandle = curl_init();
        }

        return $this->curlHandle;
    }

    /**
     * Set cURL handle
     *
     * @param resource $curlHandle
     */
    public function setCurlHandle($curlHandle)
    {
        if (is_resource($curlHandle)) {
            $this->curlHandle = $curlHandle;
        }
    }

    /**
     * Close cURL handle
     */
    public function closeCurlHandle()
    {
        if (is_resource($this->curlHandle)) {
            curl_close($this->curlHandle);
        }
    }
}

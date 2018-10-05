<?php
namespace Xicrow\PhpCurl\Interfaces;

use Xicrow\PhpCurl\Helpers\CurlOptions;
use Xicrow\PhpCurl\Response;

/**
 * Interface RequestInterface
 *
 * @package Xicrow\PhpCurl\Interfaces
 */
interface RequestInterface
{
    /**
     * Get cURL handle
     *
     * @return resource
     */
    public function curlHandle();

    /**
     * Get CurlOptions instance
     *
     * @return CurlOptions
     */
    public function curlOptions();

    /**
     * Execute cURL request
     *
     * @return Response
     */
    public function execute();
}

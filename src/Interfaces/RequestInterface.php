<?php
namespace Xicrow\PhpCurl\Interfaces;

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
    public function getCurlHandle();

    /**
     * Get cURL options
     *
     * @return array
     */
    public function getCurlOptions();

    /**
     * Execute cURL request
     *
     * @return Response
     */
    public function execute();
}

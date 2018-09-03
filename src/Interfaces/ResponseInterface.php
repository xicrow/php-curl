<?php
namespace Xicrow\PhpCurl\Interfaces;

/**
 * Interface ResponseInterface
 *
 * @package Xicrow\PhpCurl\Interfaces
 */
interface ResponseInterface
{
    /**
     * Get all HTTP headers as an array
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Get specific HTTP header
     *
     * @param string     $key
     * @param mixed|bool $default
     *
     * @return string
     */
    public function getHeader($key, $default = false);

    /**
     * Get body
     *
     * @return string
     */
    public function getBody();
}

<?php
namespace Xicrow\PhpCurl\Helpers;

/**
 * Class Headers
 *
 * @package Xicrow\PhpCurl
 */
class Headers
{
    /**
     * Array for headers
     *
     * @see https://en.wikipedia.org/wiki/List_of_HTTP_header_fields
     *
     * @var array
     */
    private $headers = [];

    /**
     * Headers constructor.
     *
     * @param array $headers
     */
    public function __construct(array $headers = [])
    {
        // Set given headers
        if (!empty($headers)) {
            $this->set($headers);
        }
    }

    /**
     * Get single or multiple headers
     *
     * @param array|mixed|null $filter
     *
     * @return array|mixed|null
     */
    public function get($filter = null)
    {
        $return = $this->headers;
        if (is_array($filter)) {
            $return = [];
            foreach ($filter as $key) {
                $return[$key] = $this->get($key);
            }
        } elseif (!is_null($filter)) {
            $return = null;
            if (array_key_exists($filter, $this->headers)) {
                $return = $this->headers[$filter];
            }
        }

        return $return;
    }

    /**
     * Get content type
     *
     * @return mixed|null
     */
    public function getContentType()
    {
        return $this->get('Content-Type');
    }

    /**
     * Get HTTP status code
     *
     * @return mixed|null
     */
    public function getHttpStatusCode()
    {
        return $this->get('Http-Status-Code');
    }

    /**
     * Get HTTP status message
     *
     * @return mixed|null
     */
    public function getHttpStatusMessage()
    {
        return $this->get('Http-Status-Message');
    }

    /**
     * Get HTTP version
     *
     * @return mixed|null
     */
    public function getHttpVersion()
    {
        return $this->get('Http-Version');
    }

    /**
     * Parse headers from cUrl response and information
     *
     * @param mixed $result
     * @param array $info
     *
     * @return $this
     */
    public function parse($result, array $info)
    {
        if (!empty($result) && $info['header_size'] > 0) {
            $header = substr($result, 0, $info['header_size']);
            $header = trim($header);
            $header = str_replace("\r\n", "\n", $header);
            $header = explode("\n", $header);
            foreach ($header as $headerIndex => $headerItem) {
                if ($headerIndex == 0) {
                    $parts = explode(' ', $headerItem);
                    $this->set('Http-Version', array_shift($parts));
                    $this->set('Http-Status-Code', array_shift($parts));
                    $this->set('Http-Status-Message', implode(' ', $parts));
                } elseif (strpos($headerItem, ':') !== false) {
                    list($key, $value) = explode(':', $headerItem);
                    $this->set(trim($key), trim($value));
                }
            }
        }

        return $this;
    }

    /**
     * Set single or multiple headers
     *
     * @param array|mixed $key
     * @param mixed       $value
     *
     * @return array|mixed|null
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $this->headers[$key] = $value;
        }

        return $this;
    }
}

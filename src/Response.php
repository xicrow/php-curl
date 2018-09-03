<?php
namespace Xicrow\PhpCurl;

use Xicrow\PhpCurl\Interfaces\ResponseInterface;

/**
 * Class Response
 *
 * @package Xicrow\PhpCurl
 */
class Response implements ResponseInterface
{
    /**
     * Information from cUrl request
     *
     * @var null|array
     */
    private $info = null;

    /**
     * Result from cUrl request
     *
     * @var null|mixed
     */
    private $result = null;

    /**
     * Headers parsed from result
     *
     * @var array
     */
    private $headers = [];

    /**
     * Body parsed from result
     *
     * @var string
     */
    private $body = '';

    /**
     * Response constructor.
     *
     * @param null|array $info
     * @param null|mixed $result
     */
    public function __construct($info = null, $result = null)
    {
        // Set information
        $this->info = $info;

        // Set result
        if (!empty($result)) {
            $this->result = $result;
            $this->parse();
        }
    }

    /**
     * Parse result to headers and body
     */
    public function parse()
    {
        // Get headers
        $headers = [];
        $header  = substr($this->result, 0, $this->info['header_size']);
        $header  = trim($header);
        $header  = str_replace("\r\n", "\n", $header);
        $header  = explode("\n", $header);
        foreach ($header as $headerIndex => $headerItem) {
            if ($headerIndex == 0) {
                $parts                          = explode(' ', $headerItem);
                $headers['Http-Version']        = array_shift($parts);
                $headers['Http-Status-Code']    = array_shift($parts);
                $headers['Http-Status-Message'] = implode(' ', $parts);
            } elseif (strpos($headerItem, ':') !== false) {
                list($key, $value) = explode(':', $headerItem);
                $headers[trim($key)] = trim($value);
            }
        }

        // Get body
        $body = $this->result;
        if (!empty($headers)) {
            $body = substr($body, $this->info['header_size']);
        }
        $body = trim($body);

        // Set parsed headers
        $this->headers = $headers;

        // Set parsed body
        $this->body = $body;
    }

    /**
     * Get info
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Get result
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get specific header
     *
     * @param string $key
     * @param bool   $default
     *
     * @return mixed
     */
    public function getHeader($key, $default = false)
    {
        // Return header if found
        if (array_key_exists($key, $this->headers)) {
            return $this->headers[$key];
        }

        // Return default value
        return $default;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get HTTP status code
     *
     * @return string|int
     */
    public function getHttpStatusCode()
    {
        // Return status code from headers if available
        if (isset($this->headers['Http-Status-Code'])) {
            return $this->headers['Http-Status-Code'];
        }

        // Return status code from information
        return $this->info['http_code'];
    }

    /**
     * Get content type
     *
     * @return mixed
     */
    public function getContentType()
    {
        // Return content type from headers if available
        if (isset($this->headers['Content-Type'])) {
            return $this->headers['Content-Type'];
        }

        // Return content type from information
        return $this->info['content_type'];
    }
}

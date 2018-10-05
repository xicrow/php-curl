<?php
namespace Xicrow\PhpCurl;

use Xicrow\PhpCurl\Helpers\Headers;
use Xicrow\PhpCurl\Interfaces\ResponseInterface;

/**
 * Class Response
 *
 * @package Xicrow\PhpCurl
 */
class Response implements ResponseInterface
{
    /**
     * Body parsed from result
     *
     * @var string
     */
    private $body = '';

    /**
     * Headers instance
     *
     * @var Headers
     */
    private $headers;

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
     * Response constructor.
     *
     * @param null|array $info
     * @param null|mixed $result
     */
    public function __construct($info = null, $result = null)
    {
        // Set Headers instance
        $this->headers = new Headers();

        // Set information
        $this->info = $info;

        // Set result
        if (!empty($result)) {
            $this->result = $result;
            $this->parse();
        }
    }

    /**
     * Get/set body
     *
     * @param null|string $body
     *
     * @return null|string
     */
    public function body($body = null)
    {
        if (!empty($body)) {
            $this->body = $body;
        }

        return $this->body;
    }

    /**
     * Get single or multiple cUrl information
     *
     * @param array|mixed|null $filter
     *
     * @return array|mixed|null
     */
    public function info($filter = null)
    {
        $return = $this->info;
        if (is_array($filter)) {
            $return = [];
            foreach ($filter as $key) {
                $return[$key] = $this->info($key);
            }
        } elseif (!is_null($filter)) {
            $return = null;
            if (array_key_exists($filter, $this->info)) {
                $return = $this->info[$filter];
            }
        }

        return $return;
    }

    /**
     * Get/set Headers instance
     *
     * @param Headers|null $headers
     *
     * @return Headers
     */
    public function headers(Headers $headers = null)
    {
        if (!empty($headers)) {
            $this->headers = $headers;
        }

        return $this->headers;
    }

    /**
     * Parse result to headers and body
     */
    public function parse()
    {
        // Parse headers
        $this->headers()->parse($this->result, $this->info);

        // Parse body
        $body = $this->result;
        if (!empty($this->headers()->get())) {
            $body = substr($body, $this->info['header_size']);
        }
        $body = trim($body);

        $this->body = $body;
    }
}

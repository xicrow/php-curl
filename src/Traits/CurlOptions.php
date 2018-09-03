<?php
namespace Xicrow\PhpCurl\Traits;

/**
 * Trait CurlOptions
 *
 * @package Xicrow\PhpCurl\Traits
 */
trait CurlOptions
{
    /**
     * Array for cURL options
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     *
     * @var array
     */
    private $curlOptions = [
        CURLOPT_HEADER         => true,
        CURLOPT_NOBODY         => false,
        CURLOPT_RETURNTRANSFER => true,
    ];

    /**
     * Get single cURL option
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     *
     * @param int   $key
     * @param mixed $default
     *
     * @return bool|mixed
     */
    public function getCurlOption($key, $default = false)
    {
        if (isset($this->curlOptions[$key])) {
            return $this->curlOptions[$key];
        }

        return $default;
    }

    /**
     * Get cURL options
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     *
     * @return array
     */
    public function getCurlOptions()
    {
        return $this->curlOptions;
    }

    /**
     * Set single cURL option
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     *
     * @param int   $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setCurlOption($key, $value)
    {
        $this->curlOptions[$key] = $value;

        return $this;
    }

    /**
     * Set multiple cURL options
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     *
     * @param array $curlOptions
     *
     * @return $this
     */
    public function setCurlOptions(array $curlOptions)
    {
        $this->curlOptions = ($curlOptions + $this->curlOptions);

        return $this;
    }
}

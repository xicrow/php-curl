<?php
namespace Xicrow\PhpCurl\Helpers;

/**
 * Class CurlOptions
 *
 * @package Xicrow\PhpCurl
 */
class CurlOptions
{
    /**
     * Array for cURL options
     *
     * @see http://php.net/manual/en/function.curl-setopt.php
     *
     * @var array
     */
    private $options = [
        CURLOPT_HEADER         => true,
        CURLOPT_NOBODY         => false,
        CURLOPT_RETURNTRANSFER => true,
    ];

    /**
     * CurlOptions constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        // Set given options
        if (!empty($options)) {
            $this->set($options);
        }
    }

    /**
     * Get single or multiple options
     *
     * @param array|mixed|null $filter
     *
     * @return array|mixed|null
     */
    public function get($filter = null)
    {
        $return = $this->options;
        if (is_array($filter)) {
            $return = [];
            foreach ($filter as $key) {
                $return[$key] = $this->get($key);
            }
        } elseif (!is_null($filter)) {
            $return = null;
            if (array_key_exists($filter, $this->options)) {
                $return = $this->options[$filter];
            }
        }

        return $return;
    }

    /**
     * Set single or multiple options
     *
     * @param array|mixed $key
     * @param mixed       $value
     *
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $this->options[$key] = $value;
        }

        return $this;
    }
}

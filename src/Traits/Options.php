<?php
namespace Xicrow\PhpCurl\Traits;

/**
 * Trait Options
 *
 * @package Xicrow\PhpCurl\Traits
 */
trait Options
{
    /**
     * Array for options
     *
     * @var array
     */
    private $options = [];

    /**
     * Get single option
     *
     * @param int   $key
     * @param mixed $default
     *
     * @return bool|mixed
     */
    public function getOption($key, $default = false)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set single option
     *
     * @param int   $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * Set multiple options
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = ($options + $this->options);

        return $this;
    }
}

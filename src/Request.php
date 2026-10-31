<?php
namespace Xicrow\PhpCurl;

use CurlHandle;
use Xicrow\PhpCurl\Helpers\CurlOptions;
use Xicrow\PhpCurl\Interfaces\RequestInterface;

/**
 * Class Request
 *
 * @package Xicrow\PhpCurl
 */
class Request implements RequestInterface
{
	/**
	 * cURL handle
	 *
	 * @var null|CurlHandle
	 */
	private $curlHandle;

	/**
	 * CurlOptions instance
	 *
	 * @var CurlOptions
	 */
	private $curlOptions;

	/**
	 * Constructor
	 *
	 * @param CurlOptions|array|null $curlOptions
	 */
	public function __construct($curlOptions = null)
	{
		// Set CurlOptions instance
		$this->curlOptions = new CurlOptions();

		// Set cUrl options if given
		if ($curlOptions instanceof CurlOptions) {
			$this->curlOptions($curlOptions);
		} elseif (is_array($curlOptions)) {
			$this->curlOptions()->set($curlOptions);
		}
	}

	/**
	 * Get/set cUrl handle
	 *
	 * @param CurlHandle|null $curlHandle
	 *
	 * @return CurlHandle
	 */
	public function curlHandle(CurlHandle|null $curlHandle = null)
	{
		if ($curlHandle instanceof CurlHandle) {
			$this->curlHandle = $curlHandle;
		}

		if (!$this->curlHandle instanceof CurlHandle) {
			$this->curlHandle = curl_init();
		}

		return $this->curlHandle;
	}

	/**
	 * Get/set CurlOptions instance
	 *
	 * @param CurlOptions|null $curlOptions
	 *
	 * @return CurlOptions
	 */
	public function curlOptions(CurlOptions|null $curlOptions = null)
	{
		if (!empty($curlOptions)) {
			$this->curlOptions = $curlOptions;
		}

		return $this->curlOptions;
	}

	/**
	 * Execute request
	 *
	 * @return Response
	 */
	public function execute()
	{
		// Set options
		curl_setopt_array($this->curlHandle(), $this->curlOptions()->get());

		// Get result
		$result = curl_exec($this->curlHandle());

		// Get information
		$info = curl_getinfo($this->curlHandle());

		// Return response
		return new Response($info, $result);
	}
}

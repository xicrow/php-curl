<?php
namespace Xicrow\PhpCurl\Interfaces;

use Xicrow\PhpCurl\Helpers\Headers;

/**
 * Interface ResponseInterface
 *
 * @package Xicrow\PhpCurl\Interfaces
 */
interface ResponseInterface
{
	/**
	 * Get/set body
	 *
	 * @return string
	 */
	public function body();

	/**
	 * Get/set Headers instance
	 *
	 * @return Headers
	 */
	public function headers();

	/**
	 * Get single or multiple cUrl information
	 *
	 * @return string
	 */
	public function info();
}

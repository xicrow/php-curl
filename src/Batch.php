<?php
namespace Xicrow\PhpCurl;

use Xicrow\PhpCurl\Interfaces\RequestInterface;
use Xicrow\PhpCurl\Traits\CurlOptions;
use Xicrow\PhpCurl\Traits\Options;

/**
 * Class Batch
 *
 * @package Xicrow\PhpCurl
 */
class Batch
{
    use CurlOptions, Options;

    /**
     * List of requests
     *
     * @var Request[]
     */
    private $requests = [];

    /**
     * List of responses
     *
     * @var Response[]
     */
    private $responses = [];

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        // Set default options
        $this->setOptions([
            'max_concurrent_requests' => 10,
        ]);

        // Set given options
        $this->setOptions($options);
    }

    /**
     * Add multiple requests
     *
     * @param array $requests
     */
    public function addRequests(array $requests)
    {
        foreach ($requests as $request) {
            if ($request instanceof RequestInterface) {
                $this->addRequest($request);
            }
        }
    }

    /**
     * Add single request
     *
     * @param RequestInterface $request
     */
    public function addRequest(RequestInterface $request)
    {
        $this->requests[] = $request;
    }

    /**
     * Get requests
     *
     * @return Request[]
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * Get responses
     *
     * @return Response[]
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Execute requests
     *
     * @return array
     */
    public function execute()
    {
        // Initialize multi handler
        $curlMultiHandle = curl_multi_init();

        // Array for handles
        $handles = [];

        // Array for errors
        $errors = [];

        // Count total requests
        $totalRequests = count($this->requests);

        // Counter for processed requests
        $processedRequests = 0;

        // Counter for batches
        $batchCounter = 0;

        // Loop until all requests have been processed
        while ($processedRequests < $totalRequests) {
            // Batch request counter
            $batchRequests = 0;

            // Loop requests
            foreach ($this->requests as $requestIndex => $request) {
                /* @var RequestInterface $request */

                // If batch request counter equals or exceeds max concurrent requests
                if ($batchRequests >= $this->options['max_concurrent_requests']) {
                    // Break, end the batch
                    break;
                }

                // If request index is below number of requests processed
                if ($requestIndex < $processedRequests) {
                    // Skip, request already processed
                    continue;
                }

                try {
                    // Set options, private options to identify request later
                    curl_setopt_array($request->getCurlHandle(), ([CURLOPT_PRIVATE => $requestIndex] + $request->getCurlOptions() + $this->getCurlOptions()));

                    // Add handle to multi handle
                    curl_multi_add_handle($curlMultiHandle, $request->getCurlHandle());

                    // Save handle
                    $handles[] = $request->getCurlHandle();
                } catch (\Exception $exception) {
                    $errors['request-' . $requestIndex] = $exception;
                }

                // Increment batch request counter
                $batchRequests++;

                // Increment processed request counter
                $processedRequests++;
            }

            // Execute requests in current batch
            $running = null;
            do {
                curl_multi_exec($curlMultiHandle, $running);
                $info = curl_multi_info_read($curlMultiHandle);
                if (is_array($info) && array_key_exists('result', $info) && $info['result'] !== CURLE_OK) {
                    $errors['handle-' . (int)$info['handle']] = $info;
                }
            } while ($running > 0);

            // Increment batch counter
            $batchCounter++;

            // If all requests have not been processed
            if ($processedRequests < $totalRequests) {
                // Short pause between batches (50 milliseconds)
                usleep(50 * 1000);
            }
        }

        // Loop handlers
        for ($i = 0; $i < count($handles); $i++) {
            // Get request index
            $requestIndex = curl_getinfo($handles[$i], CURLINFO_PRIVATE);

            // Save response
            $this->responses[$requestIndex] = new Response(curl_getinfo($handles[$i]), curl_multi_getcontent($handles[$i]));

            // Remove handler
            curl_multi_remove_handle($curlMultiHandle, $handles[$i]);
        }

        // Close multi handler
        curl_multi_close($curlMultiHandle);

        // Return errors
        return $errors;
    }
}

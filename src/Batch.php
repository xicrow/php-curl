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
     * @var array
     */
    private $requests = [];

    /**
     * List of responses
     *
     * @var array
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

        // Set options
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
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * Get responses
     *
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Execute requests
     */
    public function execute()
    {
        //        $this->options['max_concurrent_requests']

        $curlMultiHandle = curl_multi_init();
        $handles         = [];
        $errors          = [];
        foreach ($this->requests as $requestIndex => $request) {
            /* @var RequestInterface $request */
            try {
                curl_setopt_array($request->getCurlHandle(), ([CURLOPT_PRIVATE => $requestIndex] + $request->getCurlOptions() + $this->getCurlOptions()));
                curl_multi_add_handle($curlMultiHandle, $request->getCurlHandle());
                $handles[] = $request->getCurlHandle();
            } catch (\Exception $exception) {
                dd($exception);
            }
        }

        $running = null;
        do {
            curl_multi_exec($curlMultiHandle, $running);
            $info = curl_multi_info_read($curlMultiHandle);
            if ($info !== false && isset($info['handle']) && isset($info['result'])) {
                $errors[(int)$info['handle']] = $info['result'];
            }

            //    debug('Sleeping...');
            //    sleep(1);
        } while ($running > 0);

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

        return true;
    }
}

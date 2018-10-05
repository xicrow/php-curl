<?php
require('../vendor/autoload.php');

use Xicrow\PhpCurl\Batch;
use Xicrow\PhpCurl\Request;

// List of URL's
$urls = [
    'http://jsonplaceholder.typicode.com/todos/1',
    'http://jsonplaceholder.typicode.com/todos/2',
    'http://jsonplaceholder.typicode.com/todos/3',
    'http://jsonplaceholder.typicode.com/todos/4',
    'http://jsonplaceholder.typicode.com/todos/5',
];

/**
 * List of requests
 *
 * @var Request[] $requests
 */
$requests = [];
foreach ($urls as $urlIndex => $url) {
    $requests[] = new Request([CURLOPT_URL => $url]);
}

// -------------------------------------------------------------------------------------------------
// Single requests
// -------------------------------------------------------------------------------------------------
if (false) {
    $request  = current($requests);
    $response = $request->execute();
    echo '<pre>' . print_r($request, true) . '</pre>';
    echo '<pre>' . print_r($response, true) . '</pre>';
}

// -------------------------------------------------------------------------------------------------
// Batch requests
// -------------------------------------------------------------------------------------------------
if (false) {
    $batch = new Batch([
        'max_concurrent_requests' => 5,
    ]);
    $batch->addRequests($requests);
    $batch->curlOptions()->set([
        CURLOPT_CUSTOMREQUEST  => 'GET',
        CURLOPT_URL            => '',
        CURLOPT_PORT           => 80,
        CURLOPT_POSTFIELDS     => [],
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_HEADER         => true,
        CURLOPT_NOBODY         => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 3,
        CURLOPT_USERAGENT      => 'Mozilla/4.0',
    ]);
    $batch->execute();
    foreach ($batch->getRequests() as $requestIndex => $request) {
        foreach ($batch->getResponses() as $responseIndex => $response) {
            if ($requestIndex != $responseIndex) {
                continue;
            }

            echo '<pre>';
            print_r([
                //            '$request'         => $request,
                //            '$response'        => $response,
                'url'              => $request->curlOptions()->get(CURLOPT_URL),
                //                            'headers'          => $response->headers(),
                'http_status_code' => $response->headers()->getHttpStatusCode(),
                'content_type'     => $response->headers()->getContentType(),
                //                            'body'             => $response->body(),
                'total_time'       => $response->info('total_time'),
            ]);
            echo '</pre>';
        }
    }
}

// -------------------------------------------------------------------------------------------------
// Single requests vs. batch requests
// -------------------------------------------------------------------------------------------------
if (true) {
    echo '<pre>';
    echo 'Single requests';
    echo "\n";
    $timeStart = microtime(true);
    foreach ($requests as $requestIndex => $request) {
        $response = $request->execute();
        echo '#' . str_pad(($requestIndex + 1), (strlen(count($requests))), '0', STR_PAD_LEFT);
        echo ': ';
        echo $response->headers()->getHttpVersion() . ' ' . $response->headers()->getHttpStatusCode() . ' ' . $response->headers()->getHttpStatusMessage();
        echo ' ';
        echo $request->curlOptions()->get(CURLOPT_URL);
        echo "\n";
    }
    $timeStop = microtime(true);
    echo 'Elapsed time: ' . round($timeStop - $timeStart, 4) . ' sec.';
    echo '</pre>';

    echo '<pre>';
    echo 'Batch requests';
    echo "\n";
    $timeStart = microtime(true);
    $batch     = new Batch(['max_concurrent_requests' => 10]);
    $batch->addRequests($requests);
    $batchErrors = $batch->execute();
    foreach ($batch->getResponses() as $responseIndex => $response) {
        $request = $requests[$responseIndex];
        echo '#' . str_pad(($responseIndex + 1), (strlen(count($requests))), '0', STR_PAD_LEFT);
        echo ': ';
        echo $response->headers()->getHttpVersion() . ' ' . $response->headers()->getHttpStatusCode() . ' ' . $response->headers()->getHttpStatusMessage();
        echo ' ';
        echo $request->curlOptions()->get(CURLOPT_URL);
        echo "\n";
    }
    if (!empty($batchErrors)) {
        echo '$batchErrors = ' . print_r($batchErrors, true);
        echo "\n";
    }
    $timeStop = microtime(true);
    echo 'Elapsed time: ' . round($timeStop - $timeStart, 4) . ' sec.';
    echo '</pre>';
}

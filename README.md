# PHP - cURL
PHP Wrapper and tools for cURL

---

**Note:** Early version still subject to major changes

---

## Installation
The recommended way to install is through [Composer](https://getcomposer.org/).
```bash
composer require xicrow/php-curl
```

Or add directly to `composer.json`
```json
{
    "require": {
        "xicrow/php-curl": "dev-master"
    }
}
```

## Examples

### Request
Create new `Request`
```php
$request = new Request();
```

Set cUrl options on `Request` construct
```php
$request = new Request([
    CURLOPT_URL       => 'example.com',
    CURLOPT_USERAGENT => 'Mozilla/4.0',
    CURLOPT_TIMEOUT   => 5,
]);
```

Set cUrl options through `CurlOptions` instance on `Request`
```php
$request->curlOptions()->set(CURLOPT_URL, 'example.com')->set(CURLOPT_USERAGENT, 'Mozilla/4.0');
$request->curlOptions()->set([
    CURLOPT_TIMEOUT        => 5,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 3,
]);
```

### Response
Get `Response` from executing `Request`
```php
$response = $request->execute();
```

Get body from `Response`
```php
$response->body();
```

Get all headers from `Headers` instance on `Response`
```php
$response->headers()->get();
```

Get specific headers from `Headers` instance on `Response`
```php
$response->headers()->getHttpStatusCode();
$response->headers()->get('Http-Status-Code');
$response->headers()->get([
    'Http-Status-Code',
    'Http-Status-Message',
]);
```

### Batch
Create new `Batch` with options
```php
$batch = new Batch([
    'max_concurrent_requests' => 5,
]);
```

Add `Request` one at a time
```php
$batch->addRequest(new Request());
```

Or add multiple `Request`s at once
```php
$batch->addRequests([
    new Request(),
    new Request(),
    new Request(),
]);
```

Set `CurlOptions` on `Batch` which will merge with `CurlOptions` on `Request`
```php
$batch->curlOptions()->set([
    CURLOPT_CUSTOMREQUEST  => 'GET',
    CURLOPT_PORT           => 80,
]);
```

Execute `Batch` and loop `Request`s and `Response`s *(note: there are several ways to match `Request` to `Response` this is mainly for illustrative purpose)*
```php
$batch->execute();
foreach ($batch->getRequests() as $requestIndex => $request) {
    foreach ($batch->getResponses() as $responseIndex => $response) {
        // Skip if request and response index does not match
        if ($requestIndex != $responseIndex) {
            continue;
        }

        // ...
    }
}
```

## TODO
- Unit tests
- More utility methods for `CurlOptions`
- More utility methods for `Headers`
- Maybe refractor `CurlOptions` and `Headers` and how to get/set them

## License
Copyright &copy; 2018 Jan Ebsen
Licensed under the MIT license.

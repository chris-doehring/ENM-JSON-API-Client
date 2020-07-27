JSON API Client
===============
[![Build Status](https://travis-ci.org/chris-doehring/ENM-JSON-API-Client.svg?branch=master)](https://travis-ci.org/chris-doehring/ENM-JSON-API-Client)
[![Coverage Status](https://coveralls.io/repos/github/chris-doehring/ENM-JSON-API-Client/badge.svg?branch=master)](https://coveralls.io/github/chris-doehring/ENM-JSON-API-Client?branch=master)
[![Total Downloads](https://poser.pugx.org/chris-doehring/enm-json-api-client/downloads)](https://packagist.org/packages/chris-doehring/enm-json-api-client)
[![Latest Stable Version](https://poser.pugx.org/chris-doehring/enm-json-api-client/v/stable)](https://packagist.org/packages/chris-doehring/enm-json-api-client)
[![Latest Unstable Version](https://poser.pugx.org/chris-doehring/enm-json-api-client/v/unstable.png)](https://packagist.org/packages/chris-doehring/enm-json-api-client)
[![License](https://poser.pugx.org/chris-doehring/enm-json-api-client/license)](https://packagist.org/packages/chris-doehring/enm-json-api-client)

Abstract client-side PHP implementation of the [json api specification](http://jsonapi.org/format/).

It's based on the [original creation](https://github.com/eosnewmedia/JSON-API-Client) of the [eosnewmedia team](https://github.com/eosnewmedia) and the maintainer [Philipp Marien](https://github.com/pmarien).

## Installation

```sh
composer require chris-doehring/enm-json-api-client
```

It's recommended to install `guzzlehttp/guzzle` version `^7.0` as http-client and `http-interop/http-factory-guzzle` for [PSR-17](https://www.php-fig.org/psr/psr-17/) compatible factories.

```sh
composer require guzzlehttp/guzzle http-interop/http-factory-guzzle
```

You can also use any other HTTP client which implements [PSR-18](https://www.php-fig.org/psr/psr-18/).

## Usage
First you should read the docs at [chris-doehring/enm-json-api-common](https://github.com/chris-doehring/ENM-JSON-API-Common/tree/master/docs) where all basic structures are defined.

Your API client is an instance of `Enm\JsonApi\Client\JsonApiClient`, which requires a [PSR-18](https://www.php-fig.org/psr/psr-18/) HTTP client (`Psr\Http\Client\ClientInterface`) to execute requests.

```php 

$client = new JsonApiClient(
    'http://example.com/api',
    $httpClient, // instance of Psr\Http\Client\ClientInterface
    $uriFactory, // instance of Psr\Http\Message\UriFactoryInterface
    $requestFactory, // instance of Psr\Http\Message\RequestFactoryInterface
    $streamFactory, // instance of Psr\Http\Message\StreamFactoryInterface
    new Serializer(),
    new Deserializer()
);

$request = $client->createGetRequest(new Uri('/myResources/1')); // will fetch the resource at http://example.com/api/myResources/1
$request->requestInclude('myRelationship'); // include a relationship

$response = $client->execute($request);

$document = $response->document();
$myResource = $document->data()->first(); // the resource fetched by this request
$myIncludedResources = $document->included()->all(); // the included resources fetched with the include parameter

```

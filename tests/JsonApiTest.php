<?php
declare(strict_types=1);

namespace Enm\JsonApi\Client\Tests;

use Enm\JsonApi\Client\JsonApiClient;
use Enm\JsonApi\Serializer\DocumentDeserializerInterface;
use Enm\JsonApi\Serializer\DocumentSerializerInterface;
use GuzzleHttp\Psr7\Uri;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use Http\Factory\Guzzle\UriFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiTest extends TestCase
{
    public function testCreateGetRequestWithResource(): void
    {
        try {
            $client = $this->createClient('http://example.com/api');
            $request = $client->createGetRequest(new Uri('/myResources/1'));

            $this->assertEquals(
                'http://example.com/api/myResources/1',
                (string)$request->uri()
            );
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCreateGetRequestWithResources(): void
    {
        try {
            $client = $this->createClient('http://example.com');
            $request = $client->createGetRequest(new Uri('/myResources'));

            $this->assertEquals(
                'http://example.com/myResources',
                (string)$request->uri()
            );
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testCreateGetRequestWithFilteredResourcesAndInclude(): void
    {
        try {
            $client = $this->createClient('http://example.com');
            $request = $client->createGetRequest(new Uri('/myResources?include=test'));
            $request->addFilter('name', 'test');
            $request->requestInclude('myRelationship');

            $this->assertEquals(
                'http://example.com/myResources?filter%5Bname%5D=test&include=test%2CmyRelationship',
                (string)$request->uri()
            );
        } catch (Throwable $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @param string $baseUri
     * @return JsonApiClient
     */
    protected function createClient(string $baseUri): JsonApiClient
    {
        return new JsonApiClient(
            $baseUri,
            $this->createMock(ClientInterface::class),
            new UriFactory(),
            new RequestFactory(),
            new StreamFactory(),
            $this->createMock(DocumentSerializerInterface::class),
            $this->createMock(DocumentDeserializerInterface::class)
        );
    }
}

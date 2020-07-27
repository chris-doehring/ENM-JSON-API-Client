<?php
declare(strict_types=1);

namespace Enm\JsonApi\Client\Factory;

use Enm\JsonApi\Client\HttpClient\Response\HttpResponse;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Response\ResponseInterface;
use Enm\JsonApi\Serializer\DocumentDeserializerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class ResponseFactory implements ResponseFactoryInterface
{
    protected DocumentDeserializerInterface $deserializer;

    public function __construct(DocumentDeserializerInterface $deserializer)
    {
        $this->deserializer = $deserializer;
    }

    public function createResponse(PsrResponseInterface $psrResponse): ResponseInterface
    {
        $responseBody = $psrResponse->getBody()->getContents();
        $psrResponse->getBody()->rewind();

        return new HttpResponse(
            $psrResponse->getStatusCode(),
            $psrResponse->getHeaders(),
            $this->createResponseBody($responseBody),
            $psrResponse
        );
    }

    private function createResponseBody(?string $responseBody): ?DocumentInterface
    {
        $responseBody = (string)$responseBody !== '' ? json_decode($responseBody, true) : null;

        return $responseBody ? $this->deserializer->deserializeDocument($responseBody) : $responseBody;
    }
}
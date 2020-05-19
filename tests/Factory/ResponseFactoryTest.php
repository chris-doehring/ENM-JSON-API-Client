<?php
namespace Enm\JsonApi\Client\Tests\Factory;

use Enm\JsonApi\Client\Factory\ResponseFactory;
use Enm\JsonApi\Client\HttpClient\Response\HttpResponse;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Serializer\Deserializer;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseFactoryTest extends TestCase
{
    /** @var Deserializer|MockObject */
    private $deserializer = null;

    /** @var Generator */
    private $faker;

    protected function setUp(): void
    {
        $this->deserializer = $this->createMock(Deserializer::class);
        $this->faker = Factory::create();
    }

    public function testCreateResponse(): void
    {
        $psrResponse = $this->createMock(PsrResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $psrResponse->expects(self::exactly(2))->method('getBody')->willReturn($stream);

        $responseBody = [$this->faker->text];
        $responseBodyEncoded = json_encode($responseBody);
        $stream->expects(self::once())->method('getContents')->willReturn($responseBodyEncoded);
        $stream->expects(self::once())->method('rewind');

        $httpStatus = $this->faker->numberBetween();
        $psrResponse->expects(self::once())->method('getStatusCode')->willReturn($httpStatus);
        $headers = [
            $this->faker->word => $this->faker->word,
        ];
        $psrResponse->expects(self::once())->method('getHeaders')->willReturn($headers);

        $document = $this->createMock(DocumentInterface::class);
        $this->deserializer->expects(self::once())->method('deserializeDocument')->with($responseBody)->willReturn($document);

        $expected = new HttpResponse(
            $httpStatus,
            $headers,
            $document,
            $psrResponse
        );
        $this->assertEquals($expected, (new ResponseFactory($this->deserializer))->createResponse($psrResponse));
    }
}
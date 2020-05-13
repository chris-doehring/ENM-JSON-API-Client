<?php
declare(strict_types=1);

namespace Enm\JsonApi\Client\HttpClient\Response;

use Enm\JsonApi\Model\Common\KeyValueCollection;
use Enm\JsonApi\Model\Common\KeyValueCollectionInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Response\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class HttpResponse implements ResponseInterface
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var KeyValueCollectionInterface
     */
    private $headers;

    /**
     * @var DocumentInterface|null
     */
    private $document;

    /** @var PsrResponseInterface */
    protected $psrResponse;

    /**
     * @param int $status
     * @param array $headers
     * @param DocumentInterface|null $document
     */
    public function __construct(int $status, array $headers, ?DocumentInterface $document, ?PsrResponseInterface $psrResponse = null)
    {
        $this->status = $status;
        $this->headers = new KeyValueCollection();
        foreach ($headers as $header => $value) {
            if (is_array($value) && count($value) === 1) {
                $value = $value[0];
            }
            $this->headers->set($header, $value);
        }

        $this->document = $document;
        $this->psrResponse = $psrResponse;
    }

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @return KeyValueCollectionInterface
     */
    public function headers(): KeyValueCollectionInterface
    {
        return $this->headers;
    }

    /**
     * @return DocumentInterface|null
     */
    public function document(): ?DocumentInterface
    {
        return $this->document;
    }

    public function psrResponse(): ?PsrResponseInterface
    {
        return $this->psrResponse;
    }
}

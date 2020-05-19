<?php
namespace Enm\JsonApi\Client\Factory;

use Enm\JsonApi\Model\Response\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

interface ResponseFactoryInterface
{
    public function createResponse(PsrResponseInterface $psrResponse): ResponseInterface;
}
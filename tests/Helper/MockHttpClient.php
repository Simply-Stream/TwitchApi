<?php

namespace SimplyStream\TwitchApi\Tests\Helper;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class MockHttpClient implements ClientInterface
{
    private ?RequestInterface $lastRequest = null;

    /** @var list<ResponseInterface> */
    private array $queue = [];

    public function addResponse(ResponseInterface $response): void
    {
        $this->queue[] = $response;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;

        if ($this->queue === []) {
            throw new \LogicException('No queued response for request');
        }

        return array_shift($this->queue);
    }

    public function getLastRequest(): ?RequestInterface
    {
        return $this->lastRequest;
    }
}

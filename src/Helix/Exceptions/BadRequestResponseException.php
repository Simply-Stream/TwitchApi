<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Exceptions;

use Psr\Http\Message\RequestInterface;
use Throwable;

class BadRequestResponseException extends \RuntimeException
{
    public function __construct(
        private RequestInterface $request,
        private array $context,
        string $message = "",
        int $code = 400,
        ?Throwable $previous = null
    ) {
        $this->request->getBody()->rewind();

        parent::__construct($message, $code, $previous);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub;

final readonly class Transport
{
    public function __construct(
        public string $method,
        public ?string $callback = null,
        public ?string $sessionId = null,
        public ?string $conduitId = null,
        public ?string $connectedAt = null,
        public ?string $disconnectedAt = null,
    ) {
    }
}

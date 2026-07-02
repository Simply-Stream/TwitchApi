<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub;

final readonly class Transport
{
    public function __construct(
        public string $method,
        public ?string $callback = null,
    ) {
    }
}

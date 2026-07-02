<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Http;

final readonly class RawEventSubMessage
{
    public function __construct(
        public EventSubHeaders $headers,
        public string $rawBody,
    ) {
    }
}

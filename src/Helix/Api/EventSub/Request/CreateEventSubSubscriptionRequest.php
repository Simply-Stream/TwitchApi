<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub\Request;

use SimplyStream\TwitchApi\Helix\Api\EventSub\Transport;

final readonly class CreateEventSubSubscriptionRequest
{
    /** @param array<string, mixed> $condition */
    public function __construct(
        public string $type,
        public string $version,
        public array $condition,
        public Transport $transport,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class EventSubSubscription
{
    public function __construct(
        public string $type,
        public string $version,
        public string $condition
    ) {
    }
}

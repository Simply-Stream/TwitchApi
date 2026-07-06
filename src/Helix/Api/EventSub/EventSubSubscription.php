<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub;

final readonly class EventSubSubscription
{
    /** @param array<string, mixed> $condition */
    public function __construct(
        public string $id,
        public SubscriptionStatus $status,
        public string $type,
        public string $version,
        public array $condition,
        public Transport $transport,
        public string $createdAt,
        public int $cost,
    ) {
    }
}

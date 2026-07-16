<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

final readonly class EventSubMetadata
{
    public function __construct(
        public string $messageId,
        public EventSubMessageType $messageType,
        public int $messageRetry,
        public \DateTimeImmutable $timestamp,
        public string $subscriptionType,
        public string $subscriptionVersion,
    ) {
    }
}

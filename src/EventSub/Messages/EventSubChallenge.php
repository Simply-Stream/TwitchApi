<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

final readonly class EventSubChallenge implements EventSubMessageInterface
{
    public function __construct(
        private EventSubMetadata $metadata,
        public string $challenge,
    ) {
    }

    public function metadata(): EventSubMetadata
    {
        return $this->metadata;
    }
}

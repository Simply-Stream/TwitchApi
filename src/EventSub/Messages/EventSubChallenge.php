<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

use SimplyStream\TwitchApi\EventSub\Subscription;

final readonly class EventSubChallenge implements EventSubMessageInterface
{
    public function __construct(
        private EventSubMetadata $metadata,
        private Subscription $subscription,
        public string $challenge,
    ) {
    }

    public function metadata(): EventSubMetadata
    {
        return $this->metadata;
    }

    public function subscription(): Subscription
    {
        return $this->subscription;
    }
}

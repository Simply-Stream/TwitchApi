<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Subscription;

final readonly class EventSubNotification implements EventSubMessageInterface
{
    public function __construct(
        private EventSubMetadata $metadata,
        public Subscription $subscription,
        public EventInterface $event,
    ) {
    }

    public function metadata(): EventSubMetadata
    {
        return $this->metadata;
    }
}

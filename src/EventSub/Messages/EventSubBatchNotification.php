<?php

namespace SimplyStream\TwitchApi\EventSub\Messages;

use SimplyStream\TwitchApi\EventSub\Subscription;

final readonly class EventSubBatchNotification implements EventSubMessageInterface
{
    /**
     * @param BatchedEvent[] $events
     */
    public function __construct(
        private EventSubMetadata $metadata,
        private Subscription $subscription,
        public array $events,
    ) {}

    public function metadata(): EventSubMetadata { return $this->metadata; }
    public function subscription(): Subscription { return $this->subscription; }
}

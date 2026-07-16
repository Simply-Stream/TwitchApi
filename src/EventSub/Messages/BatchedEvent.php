<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Messages;

use SimplyStream\TwitchApi\EventSub\EventInterface;

final readonly class BatchedEvent
{
    public function __construct(
        public string $id,
        public EventInterface $event,
    ) {
    }
}

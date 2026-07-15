<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ConduitShardDisabledCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;
use SimplyStream\TwitchApi\EventSub\Events\ConduitShardDisabled\Transport;

#[EventSubSubscription(type: 'conduit.shard.disabled', version: '1', condition: ConduitShardDisabledCondition::class)]
final readonly class ConduitShardDisabledEvent implements EventInterface
{
    /**
     * @param string    $conduitId The ID of the conduit.
     * @param string    $shardId   The ID of the disabled shard.
     * @param string    $status    The new status of the transport.
     * @param Transport $transport The disabled transport.
     */
    public function __construct(
        public string $conduitId,
        public string $shardId,
        public string $status,
        public Transport $transport,
    ) {
    }
}

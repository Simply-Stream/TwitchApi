<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelRaidCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.raid', version: '1', condition: ChannelRaidCondition::class)]
final readonly class ChannelRaidEvent implements EventInterface
{
    /**
     * @param string $fromBroadcasterUserId    The broadcaster ID that created the raid.
     * @param string $fromBroadcasterUserLogin The broadcaster login that created the raid.
     * @param string $fromBroadcasterUserName  The broadcaster display name that created the raid.
     * @param string $toBroadcasterUserId      The broadcaster ID that received the raid.
     * @param string $toBroadcasterUserLogin   The broadcaster login that received the raid.
     * @param string $toBroadcasterUserName    The broadcaster display name that received the raid.
     * @param int    $viewers                  The number of viewers in the raid.
     */
    public function __construct(
        public string $fromBroadcasterUserId,
        public string $fromBroadcasterUserLogin,
        public string $fromBroadcasterUserName,
        public string $toBroadcasterUserId,
        public string $toBroadcasterUserLogin,
        public string $toBroadcasterUserName,
        public int $viewers
    ) {
    }
}

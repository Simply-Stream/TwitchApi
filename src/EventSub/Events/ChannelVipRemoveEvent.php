<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelVipRemoveCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.vip.remove', version: '1', condition: ChannelVipRemoveCondition::class)]
final readonly class ChannelVipRemoveEvent implements EventInterface
{
    /**
     * @param string $userId               The ID of the user who was removed as a VIP.
     * @param string $userLogin            The login of the user who was removed as a VIP.
     * @param string $userName             The display name of the user who was removed as a VIP.
     * @param string $broadcasterUserId    The ID of the broadcaster.
     * @param string $broadcasterUserLogin The login of the broadcaster.
     * @param string $broadcasterUserName  The display name of the broadcaster.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
    ) {
    }
}

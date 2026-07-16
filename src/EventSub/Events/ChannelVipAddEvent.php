<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelVipAddCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.vip.add', version: '1', condition: ChannelVipAddCondition::class)]
final readonly class ChannelVipAddEvent implements EventInterface
{
    /**
     * @param string $userId               The ID of the user who was added as a VIP.
     * @param string $userLogin            The login of the user who was added as a VIP.
     * @param string $userName             The display name of the user who was added as a VIP.
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

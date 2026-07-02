<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelSubscribeCondition;

#[EventSubSubscription(type: 'channel.subscribe', version: '1', condition: ChannelSubscribeCondition::class)]
final readonly class ChannelSubscribeEvent
{
    /**
     * @param string $userId               The user ID for the user who subscribed to the specified channel.
     * @param string $userLogin            The user login for the user who subscribed to the specified channel.
     * @param string $userName             The user display name for the user who subscribed to the specified channel.
     * @param string $broadcasterUserId    The requested broadcaster ID.
     * @param string $broadcasterUserLogin The requested broadcaster login.
     * @param string $broadcasterUserName  The requested broadcaster display name.
     * @param string $tier                 The tier of the subscription. Valid values are 1000, 2000, and 3000.
     * @param bool   $isGift               Whether the subscription is a gift.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $tier,
        public bool $isGift
    ) {
    }
}

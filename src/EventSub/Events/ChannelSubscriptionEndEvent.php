<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelSubscriptionEndCondition;

#[EventSubSubscription(type: 'channel.subscription.end', version: '1', condition: ChannelSubscriptionEndCondition::class)]
final readonly class ChannelSubscriptionEndEvent
{
    /**
     * @param string $userId               The user ID for the user whose subscription ended.
     * @param string $userLogin            The user login for the user whose subscription ended.
     * @param string $userName             The user display name for the user whose subscription ended.
     * @param string $broadcasterUserId    The broadcaster user ID.
     * @param string $broadcasterUserLogin The broadcaster login.
     * @param string $broadcasterUserName  The broadcaster display name.
     * @param string $tier                 The tier of the subscription that ended. Valid values are 1000, 2000, and
     *                                     3000.
     * @param bool   $isGift               Whether the subscription was a gift.
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

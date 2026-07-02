<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChannelChatNotification;

final readonly class CommunityGiftSubscription
{
    /**
     * @param string   $id              The ID of the associated community gift.
     * @param int      $total           Number of subscriptions being gifted.
     * @param string   $subTier         The type of subscription plan being used. Possible values are:
     *                                  - 1000 — First level of paid subscription
     *                                  - 2000 — Second level of paid subscription
     *                                  - 3000 — Third level of paid subscription
     * @param int|null $cumulativeTotal Optional. The amount of gifts the gifter has given in this channel. Null if
     *                                  anonymous.
     */
    public function __construct(
        public string $id,
        public int $total,
        public string $subTier,
        public ?int $cumulativeTotal = null
    ) {
    }
}

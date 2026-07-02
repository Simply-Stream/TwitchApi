<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelSubscriptionGiftCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.subscription.gift', version: '1', condition: ChannelSubscriptionGiftCondition::class)]
final readonly class ChannelSubscriptionGiftEvent implements EventInterface
{
    /**
     * @param string      $broadcasterUserId    The broadcaster user ID.
     * @param string      $broadcasterUserLogin The broadcaster login.
     * @param string      $broadcasterUserName  The broadcaster display name.
     * @param int         $total                The number of subscriptions in the subscription gift.
     * @param string      $tier                 The tier of subscriptions in the subscription gift.
     * @param bool        $isAnonymous          Whether the subscription gift was anonymous.
     * @param string|null $userId               The user ID of the user who sent the subscription gift. Set to null if
     *                                          it was an anonymous subscription gift.
     * @param string|null $userLogin            The user login of the user who sent the gift. Set to null if it was an
     *                                          anonymous subscription gift.
     * @param string|null $userName             The user display name of the user who sent the gift. Set to null if it
     *                                          was an anonymous subscription gift.
     * @param int|null    $cumulativeTotal      The number of subscriptions gifted by this user in the channel. This
     *                                          value is null for anonymous gifts or if the gifter has opted out of
     *                                          sharing this information.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public int $total,
        public string $tier,
        public bool $isAnonymous,
        public ?string $userId = null,
        public ?string $userLogin = null,
        public ?string $userName = null,
        public ?int $cumulativeTotal = null
    ) {
    }
}

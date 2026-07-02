<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelSubscriptionMessageCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.subscription.message', version: '1', condition: ChannelSubscriptionMessageCondition::class)]
final readonly class ChannelSubscriptionMessageEvent implements EventInterface
{
    /**
     * @param string   $userId               The user ID of the user who sent a resubscription chat message.
     * @param string   $userLogin            The user login of the user who sent a resubscription chat message.
     * @param string   $userName             The user display name of the user who a resubscription chat message.
     * @param string   $broadcasterUserId    The broadcaster user ID.
     * @param string   $broadcasterUserLogin The broadcaster login.
     * @param string   $broadcasterUserName  The broadcaster display name.
     * @param string   $tier                 The tier of the user’s subscription.
     * @param Message  $message              An object that contains the resubscription message and emote information
     *                                       needed to recreate the message.
     * @param int      $cumulativeMonths     The total number of months the user has been subscribed to the channel.
     * @param int      $durationMonths       The month duration of the subscription.
     * @param int|null $streakMonths         The number of consecutive months the user’s current subscription has been
     *                                       active. This value is null if the user has opted out of sharing this
     *                                       information.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $tier,
        public Message $message,
        public int $cumulativeMonths,
        public int $durationMonths,
        public ?int $streakMonths = null
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelFollowCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.follow', version: '1', condition: ChannelFollowCondition::class)]
final readonly class ChannelFollowEvent implements EventInterface
{
    /**
     * @param string             $userId               The user ID for the user now following the specified channel.
     * @param string             $userLogin            The user login for the user now following the specified channel.
     * @param string             $userName             The user display name for the user now following the specified
     *                                                 channel.
     * @param string             $broadcasterUserId    The requested broadcaster ID.
     * @param string             $broadcasterUserLogin The requested broadcaster login.
     * @param string             $broadcasterUserName  The requested broadcaster display name.
     * @param \DateTimeImmutable $followedAt           RFC3339 timestamp of when the follow occurred.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public \DateTimeImmutable $followedAt,
    ) {
    }
}

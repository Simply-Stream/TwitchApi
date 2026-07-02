<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelUnbanCondition;

#[EventSubSubscription(type: 'channel.unban', version: '1', condition: ChannelUnbanCondition::class)]
final readonly class ChannelUnbanEvent
{
    /**
     * @param string $userId               The user id for the user who was unbanned on the specified channel.
     * @param string $userLogin            The user login for the user who was unbanned on the specified channel.
     * @param string $userName             The user display name for the user who was unbanned on the specified channel.
     * @param string $broadcasterUserId    The requested broadcaster ID.
     * @param string $broadcasterUserLogin The requested broadcaster login.
     * @param string $broadcasterUserName  The requested broadcaster display name.
     * @param string $moderatorUserId      The user ID of the issuer of the unban.
     * @param string $moderatorUserLogin   The user login of the issuer of the unban.
     * @param string $moderatorUserName    The user name of the issuer of the unban.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
    ) {
    }
}

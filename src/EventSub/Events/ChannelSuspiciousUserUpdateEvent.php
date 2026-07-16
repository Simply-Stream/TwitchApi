<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelSuspiciousUserUpdateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.suspicious_user.update', version: '1', condition: ChannelSuspiciousUserUpdateCondition::class)]
final readonly class ChannelSuspiciousUserUpdateEvent implements EventInterface
{
    /**
     * @param string $broadcasterUserId    The ID of the channel where the treatment for a suspicious user was
     *                                     updated.
     * @param string $broadcasterUserName  The display name of the channel where the treatment for a suspicious user
     *                                     was updated.
     * @param string $broadcasterUserLogin The login of the channel where the treatment for a suspicious user was
     *                                     updated.
     * @param string $moderatorUserId      The ID of the moderator that updated the treatment for a suspicious user.
     * @param string $moderatorUserName    The display name of the moderator that updated the treatment for a
     *                                     suspicious user.
     * @param string $moderatorUserLogin   The login of the moderator that updated the treatment for a suspicious
     *                                     user.
     * @param string $userId               The ID of the suspicious user whose treatment was updated.
     * @param string $userName             The display name of the suspicious user whose treatment was updated.
     * @param string $userLogin            The login of the suspicious user whose treatment was updated.
     * @param string $lowTrustStatus       The status set for the suspicious user. One of: none, active_monitoring,
     *                                     restricted.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $moderatorUserId,
        public string $moderatorUserName,
        public string $moderatorUserLogin,
        public string $userId,
        public string $userName,
        public string $userLogin,
        public string $lowTrustStatus,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelGuestStarSessionEndCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.guest_star_session.end', version: 'beta', condition: ChannelGuestStarSessionEndCondition::class)]
final readonly class ChannelGuestStarSessionEndEvent implements EventInterface
{
    /**
     * @param string            $broadcasterUserId    The non-host broadcaster user ID.
     * @param string            $broadcasterUserName  The non-host broadcaster display name.
     * @param string            $broadcasterUserLogin The non-host broadcaster login.
     * @param string            $sessionId            ID representing the unique session that was started.
     * @param DateTimeInterface $startedAt            RFC3339 timestamp indicating the time the session began.
     * @param DateTimeInterface $endedAt              RFC3339 timestamp indicating the time the session ended.
     * @param string            $hostUserId           User ID of the host channel.
     * @param string            $hostUserName         The host display name.
     * @param string            $hostUserLogin        The host login.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $sessionId,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $endedAt,
        public string $hostUserId,
        public string $hostUserName,
        public string $hostUserLogin,
    ) {
    }
}

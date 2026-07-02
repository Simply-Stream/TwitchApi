<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelGuestStarSessionEndCondition;

#[EventSubSubscription(type: 'channel.guest_star_session.end', version: '1', condition: ChannelGuestStarSessionEndCondition::class)]
final readonly class ChannelGuestStarSessionEndEvent
{
    /**
     * @param string            $broadcasterUserId    The broadcaster user ID
     * @param string            $broadcasterUserName  The broadcaster display name
     * @param string            $broadcasterUserLogin The broadcaster login
     * @param string            $sessionId            ID representing the unique session that was started.
     * @param DateTimeInterface $startedAt            RFC3339 timestamp indicating the time the session began.
     * @param DateTimeInterface $endedAt              RFC3339 timestamp indicating the time the session ended.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $sessionId,
        public DateTimeInterface $startedAt,
        public DateTimeInterface $endedAt
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ChannelGuestStarSessionBeginCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.guest_star_session.begin', version: 'beta', condition: ChannelGuestStarSessionBeginCondition::class)]
final readonly class ChannelGuestStarSessionBeginEvent implements EventInterface
{
    /**
     * @param string            $broadcasterUserId    The broadcaster user ID
     * @param string            $broadcasterUserName  The broadcaster display name
     * @param string            $broadcasterUserLogin The broadcaster login
     * @param string            $sessionId            ID representing the unique session that was started.
     * @param DateTimeInterface $startedAt            RFC3339 timestamp indicating the time the session began.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserName,
        public string $broadcasterUserLogin,
        public string $sessionId,
        public DateTimeInterface $startedAt
    ) {
    }
}

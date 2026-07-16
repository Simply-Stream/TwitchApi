<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ShoutoutReceiveCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.shoutout.receive', version: '1', condition: ShoutoutReceiveCondition::class)]
final readonly class ShoutoutReceiveEvent implements EventInterface
{
    /**
     * @param string             $broadcasterUserId        An ID that identifies the broadcaster that received the
     *                                                     Shoutout.
     * @param string             $broadcasterUserLogin     The broadcaster’s login name.
     * @param string             $broadcasterUserName      The broadcaster’s display name.
     * @param string             $fromBroadcasterUserId    An ID that identifies the broadcaster that sent the
     *                                                     Shoutout.
     * @param string             $fromBroadcasterUserLogin The broadcaster’s login name.
     * @param string             $fromBroadcasterUserName  The broadcaster’s display name.
     * @param int                $viewerCount              The number of users that were watching the
     *                                                     from-broadcaster’s stream at the time of the Shoutout.
     * @param \DateTimeInterface $startedAt                The UTC timestamp (in RFC3339 format) of when the moderator
     *                                                     sent the Shoutout.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $fromBroadcasterUserId,
        public string $fromBroadcasterUserLogin,
        public string $fromBroadcasterUserName,
        public int $viewerCount,
        public \DateTimeInterface $startedAt
    ) {
    }
}

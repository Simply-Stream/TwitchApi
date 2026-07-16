<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\ShoutoutCreateCondition;
use SimplyStream\TwitchApi\EventSub\EventInterface;

#[EventSubSubscription(type: 'channel.shoutout.create', version: '1', condition: ShoutoutCreateCondition::class)]
final readonly class ShoutoutCreateEvent implements EventInterface
{
    /**
     * @param string             $broadcasterUserId      An ID that identifies the broadcaster that sent the Shoutout.
     * @param string             $broadcasterUserLogin   The broadcaster’s login name.
     * @param string             $broadcasterUserName    The broadcaster’s display name.
     * @param string             $toBroadcasterUserId    An ID that identifies the broadcaster that received the
     *                                                   Shoutout.
     * @param string             $toBroadcasterUserLogin The broadcaster’s login name.
     * @param string             $toBroadcasterUserName  The broadcaster’s display name.
     * @param string             $moderatorUserId        An ID that identifies the moderator that sent the Shoutout. If
     *                                                   the broadcaster sent the Shoutout, this ID is the same as the
     *                                                   ID in broadcaster_user_id.
     * @param string             $moderatorUserLogin     The moderator’s login name.
     * @param string             $moderatorUserName      The moderator’s display name.
     * @param int                $viewerCount            The number of users that were watching the broadcaster’s
     *                                                   stream at the time of the Shoutout.
     * @param \DateTimeInterface $startedAt              The UTC timestamp (in RFC3339 format) of when the moderator
     *                                                   sent the Shoutout.
     * @param \DateTimeInterface $cooldownEndsAt         The UTC timestamp (in RFC3339 format) of when the broadcaster
     *                                                   may send a Shoutout to a different broadcaster.
     * @param \DateTimeInterface $targetCooldownEndsAt   The UTC timestamp (in RFC3339 format) of when the broadcaster
     *                                                   may send another Shoutout to the broadcaster in
     *                                                   to_broadcaster_user_id.
     */
    public function __construct(
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $toBroadcasterUserId,
        public string $toBroadcasterUserLogin,
        public string $toBroadcasterUserName,
        public string $moderatorUserId,
        public string $moderatorUserLogin,
        public string $moderatorUserName,
        public int $viewerCount,
        public \DateTimeInterface $startedAt,
        public \DateTimeInterface $cooldownEndsAt,
        public \DateTimeInterface $targetCooldownEndsAt,
    ) {
    }
}

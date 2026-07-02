<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events;

use DateTimeInterface;
use SimplyStream\TwitchApi\EventSub\Attributes\EventSubSubscription;
use SimplyStream\TwitchApi\EventSub\Conditions\StreamOfflineCondition;

#[EventSubSubscription(type: 'stream.offline', version: '1', condition: StreamOfflineCondition::class)]
final readonly class StreamOnlineEvent
{
    /**
     * @param string            $id                    The id of the stream.
     * @param string            $broadcasterUserId     The broadcaster’s user id.
     * @param string            $broadcasterUserLogin  The broadcaster’s user login.
     * @param string            $broadcasterUserName   The broadcaster’s user display name.
     * @param string            $type                  The stream type. Valid values are: live, playlist, watch_party,
     *                                                 premiere, rerun.
     * @param DateTimeInterface $startedAt             The timestamp at which the stream went online at.
     */
    public function __construct(
        public string $id,
        public string $broadcasterUserId,
        public string $broadcasterUserLogin,
        public string $broadcasterUserName,
        public string $type,
        public DateTimeInterface $startedAt
    ) {
    }
}

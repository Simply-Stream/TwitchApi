<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChatNotification;

final readonly class WatchStreak
{
    /**
     * @param int $streakCount          The number of consecutive broadcasts for which the user has been watching.
     * @param int $channelPointsAwarded The number of channel points awarded for the Watch Streak milestone.
     */
    public function __construct(
        public int $streakCount,
        public int $channelPointsAwarded,
    ) {
    }
}

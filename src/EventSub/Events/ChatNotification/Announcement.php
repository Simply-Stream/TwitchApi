<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChatNotification;

final readonly class Announcement
{
    /**
     * @param string $color Color of the announcement.
     */
    public function __construct(
        public string $color
    ) {
    }
}

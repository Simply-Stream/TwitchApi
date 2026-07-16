<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ChatNotification;

final readonly class Modiversary
{
    /**
     * @param int $months The number of months the user has been a moderator in this channel.
     */
    public function __construct(
        public int $months,
    ) {
    }
}

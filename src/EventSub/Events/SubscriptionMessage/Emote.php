<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\SubscriptionMessage;

final readonly class Emote
{
    /**
     * @param int    $begin The index of where the Emote starts in the text.
     * @param int    $end   The index of where the Emote ends in the text.
     * @param string $id    The emote ID.
     */
    public function __construct(
        public int $begin,
        public int $end,
        public string $id
    ) {
    }
}

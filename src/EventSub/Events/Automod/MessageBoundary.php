<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Automod;

final readonly class MessageBoundary
{
    /**
     * @param int $startPos Index in the message for the start of the problem (0 indexed, inclusive).
     * @param int $endPos   Index in the message for the end of the problem (0 indexed, inclusive).
     */
    public function __construct(
        public int $startPos,
        public int $endPos,
    ) {
    }
}

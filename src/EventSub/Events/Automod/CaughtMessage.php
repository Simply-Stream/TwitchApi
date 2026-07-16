<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Automod;

final readonly class CaughtMessage
{
    /**
     * @param string            $category   The category of the caught message.
     * @param int               $level      The level of severity (1-4).
     * @param MessageBoundary[] $boundaries The bounds of the text that caused the message to be caught.
     */
    public function __construct(
        public string $category,
        public int $level,
        public array $boundaries,
    ) {
    }
}

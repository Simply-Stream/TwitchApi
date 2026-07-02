<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class Message
{
    /**
     * @param string            $text      The chat message in plain text.
     * @param MessageFragment[] $fragments Ordered list of chat message fragments.
     */
    public function __construct(
        public string $text,
        public array $fragments
    ) {
    }
}

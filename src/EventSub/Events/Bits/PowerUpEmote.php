<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Bits;

final readonly class PowerUpEmote
{
    /**
     * @param string $id   The ID that uniquely identifies this emote.
     * @param string $name The human readable emote token.
     */
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}

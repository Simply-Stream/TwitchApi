<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class Cheermote
{
    /**
     * @param string $prefix The name portion of the Cheermote string that you use in chat to cheer Bits. The full
     *                       Cheermote string is the concatenation of {prefix} + {number of Bits}. For example, if the
     *                       prefix is “Cheer” and you want to cheer 100 Bits, the full Cheermote string is Cheer100.
     *                       When the Cheermote string is entered in chat, Twitch converts it to the image associated
     *                       with the Bits tier that was cheered.
     * @param int    $bits   The amount of bits cheered.
     * @param int    $tier   The tier level of the cheermote.
     */
    public function __construct(
        public string $prefix,
        public int $bits,
        public int $tier
    ) {
    }
}

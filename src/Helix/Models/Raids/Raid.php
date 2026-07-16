<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Raids;

use DateTimeInterface;

final readonly class Raid
{
    /**
     * @param DateTimeInterface $createdAt The UTC date and time, in RFC3339 format, of when the raid was requested.
     * @param bool              $isMature  A Boolean value that indicates whether the channel being raided contains
     *                                     mature content.
     */
    public function __construct(
        public DateTimeInterface $createdAt,
        public bool $isMature,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Videos;

final readonly class MutedSegment
{
    /**
     * @param int $offset   The offset, in seconds, from the beginning of the video to where the muted segment begins.
     * @param int $duration The duration of the muted segment, in seconds.
     */
    public function __construct(
        public int $offset,
        public int $duration,
    ) {
    }
}

<?php

namespace SimplyStream\TwitchApi\Helix\Models\Videos;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class MutedSegment
{
    use SerializesModels;

    /**
     * @param int $offset   The offset, in seconds, from the beginning of the video to where the muted segment begins.
     * @param int $duration The duration of the muted segment, in seconds.
     */
    public function __construct(
        private int $offset,
        private int $duration
    ) {
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }
}

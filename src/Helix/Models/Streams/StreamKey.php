<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

final readonly class StreamKey
{
    /**
     * @param string $streamKey The channel’s stream key.
     */
    public function __construct(
        public string $streamKey,
    ) {
    }
}

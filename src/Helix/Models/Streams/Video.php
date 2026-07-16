<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

final readonly class Video
{
    /**
     * @param string       $videoId An ID that identifies this video.
     * @param list<Marker> $markers The list of markers in this video, in ascending order by when the marker was
     *                              created.
     */
    public function __construct(
        public string $videoId,
        public array $markers,
    ) {
    }
}

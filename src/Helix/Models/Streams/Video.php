<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Video
{
    use SerializesModels;

    /**
     * @param string   $videoId    An ID that identifies this video.
     * @param Marker[] $markers    The list of markers in this video. The list in ascending order by when the marker was
     *                             created.
     */
    public function __construct(
        private string $videoId,
        private array $markers
    ) {
    }

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function getMarkers(): array
    {
        return $this->markers;
    }
}

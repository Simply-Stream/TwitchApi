<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

final readonly class StreamMarker
{
    /**
     * @param string     $userId    The ID of the user that created the marker.
     * @param string     $userName  The user’s display name.
     * @param string     $userLogin The user’s login name.
     * @param list<Video> $videos    A list of videos that contain markers. The list contains a single video.
     */
    public function __construct(
        public string $userId,
        public string $userName,
        public string $userLogin,
        public array $videos,
    ) {
    }
}

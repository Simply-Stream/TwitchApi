<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class StreamMarker
{
    use SerializesModels;

    /**
     * @param string  $userId    The ID of the user that created the marker.
     * @param string  $userName  The user’s display name.
     * @param string  $userLogin The user’s login name.
     * @param Video[] $videos    A list of videos that contain markers. The list contains a single video.
     */
    public function __construct(
        private string $userId,
        private string $userName,
        private string $userLogin,
        private array $videos,
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserLogin(): string
    {
        return $this->userLogin;
    }

    public function getVideos(): array
    {
        return $this->videos;
    }
}

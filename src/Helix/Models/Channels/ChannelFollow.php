<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Channels;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChannelFollow
{
    use SerializesModels;

    /**
     * @param DateTimeImmutable $followedAt The UTC timestamp when the user started following the broadcaster.
     * @param string            $userId     An ID that uniquely identifies the user that’s following the broadcaster.
     * @param string            $userLogin  The user’s login name.
     * @param string            $userName   The user’s display name.
     */
    public function __construct(
        private DateTimeImmutable $followedAt,
        private string $userId,
        private string $userLogin,
        private string $userName
    ) {
    }

    public function getFollowedAt(): DateTimeImmutable
    {
        return $this->followedAt;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserLogin(): string
    {
        return $this->userLogin;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}

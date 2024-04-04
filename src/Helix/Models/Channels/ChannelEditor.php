<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Channels;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChannelEditor
{
    use SerializesModels;

    /**
     * @param string            $userId     An ID that uniquely identifies a user with editor permissions.
     * @param string            $userName   The user’s display name.
     * @param DateTimeInterface $createdAt  The date and time, in RFC3339 format, when the user became one of the
     *                                      broadcaster’s editors.
     */
    public function __construct(
        private string $userId,
        private string $userName,
        private DateTimeInterface $createdAt
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

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}

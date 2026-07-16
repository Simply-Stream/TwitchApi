<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Channels;

use DateTimeInterface;

final readonly class ChannelEditor
{
    /**
     * @param string            $userId    An ID that uniquely identifies a user with editor permissions.
     * @param string            $userName  The user’s display name.
     * @param DateTimeInterface $createdAt The date and time, in RFC3339 format, when the user became one of the
     *                                     broadcaster’s editors.
     */
    public function __construct(
        public string $userId,
        public string $userName,
        public DateTimeInterface $createdAt,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use DateTimeInterface;

final readonly class BannedUser
{
    /**
     * @param string                 $userId         The ID of the banned user.
     * @param string                 $userLogin      The banned user’s login name.
     * @param string                 $userName       The banned user’s display name.
     * @param DateTimeInterface|null $expiresAt      The UTC date and time (in RFC3339 format) of when the timeout
     *                                               expires. Null if the user is permanently banned.
     * @param DateTimeInterface      $createdAt      The UTC date and time (in RFC3339 format) of when the user was
     *                                               banned.
     * @param string                 $reason         The reason the user was banned or put in a timeout if the
     *                                               moderator provided one.
     * @param string                 $moderatorId    The ID of the moderator that banned the user or put them in a
     *                                               timeout.
     * @param string                 $moderatorLogin The moderator’s login name.
     * @param string                 $moderatorName  The moderator’s display name.
     */
    public function __construct(
        public string $userId,
        public string $userLogin,
        public string $userName,
        public ?DateTimeInterface $expiresAt,
        public DateTimeInterface $createdAt,
        public string $reason,
        public string $moderatorId,
        public string $moderatorLogin,
        public string $moderatorName,
    ) {
    }
}

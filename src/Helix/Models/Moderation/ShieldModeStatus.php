<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use DateTimeInterface;

final readonly class ShieldModeStatus
{
    /**
     * @param bool              $isActive        A Boolean value that determines whether Shield Mode is active.
     * @param string            $moderatorId     An ID that identifies the moderator that last activated Shield Mode.
     * @param string            $moderatorLogin  The moderator’s login name.
     * @param string            $moderatorName   The moderator’s display name.
     * @param DateTimeInterface $lastActivatedAt The UTC timestamp (in RFC3339 format) of when Shield Mode was last
     *                                           activated.
     */
    public function __construct(
        public bool $isActive,
        public string $moderatorId,
        public string $moderatorLogin,
        public string $moderatorName,
        public DateTimeInterface $lastActivatedAt,
    ) {
    }
}

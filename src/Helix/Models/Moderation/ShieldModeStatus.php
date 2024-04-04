<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ShieldModeStatus
{
    use SerializesModels;

    /**
     * @param bool              $isActive         A Boolean value that determines whether Shield Mode is active. Is
     *                                            true if Shield Mode is active; otherwise, false.
     * @param string            $moderatorId      An ID that identifies the moderator that last activated Shield Mode.
     * @param string            $moderatorLogin   The moderator’s login name.
     * @param string            $moderatorName    The moderator’s display name.
     * @param DateTimeInterface $lastActivatedAt  The UTC timestamp (in RFC3339 format) of when Shield Mode was last
     *                                            activated.
     */
    public function __construct(
        private bool $isActive,
        private string $moderatorId,
        private string $moderatorLogin,
        private string $moderatorName,
        private DateTimeInterface $lastActivatedAt
    ) {
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getModeratorId(): string
    {
        return $this->moderatorId;
    }

    public function getModeratorLogin(): string
    {
        return $this->moderatorLogin;
    }

    public function getModeratorName(): string
    {
        return $this->moderatorName;
    }

    public function getLastActivatedAt(): DateTimeInterface
    {
        return $this->lastActivatedAt;
    }
}

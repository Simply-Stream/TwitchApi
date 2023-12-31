<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Raids;

use DateTimeImmutable;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Raid
{
    use SerializesModels;

    /**
     * @param DateTimeImmutable $createdAt  The UTC date and time, in RFC3339 format, of when the raid was requested.
     * @param bool              $isMature   A Boolean value that indicates whether the channel being raided contains
     *                                      mature content.
     */
    public function __construct(
        private DateTimeImmutable $createdAt,
        private bool $isMature
    ) {
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isMature(): bool
    {
        return $this->isMature;
    }
}

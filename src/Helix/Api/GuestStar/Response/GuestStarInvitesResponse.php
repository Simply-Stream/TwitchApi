<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Response;

use SimplyStream\TwitchApi\Helix\Models\GuestStar\GuestStarInvite;

final readonly class GuestStarInvitesResponse
{
    /** @param list<GuestStarInvite> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

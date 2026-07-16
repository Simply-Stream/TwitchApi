<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Response;

use SimplyStream\TwitchApi\Helix\Models\GuestStar\GuestStarSession;

final readonly class GuestStarSessionResponse
{
    /** @param list<GuestStarSession> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

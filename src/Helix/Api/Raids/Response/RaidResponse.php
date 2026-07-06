<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Raids\Response;

use SimplyStream\TwitchApi\Helix\Models\Raids\Raid;

final readonly class RaidResponse
{
    /** @param list<Raid> $data */
    public function __construct(
        public array $data,
    ) {}
}

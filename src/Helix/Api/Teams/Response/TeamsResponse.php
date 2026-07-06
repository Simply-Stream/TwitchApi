<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Teams\Response;

use SimplyStream\TwitchApi\Helix\Models\Teams\Team;

final readonly class TeamsResponse
{
    /** @param list<Team> $data */
    public function __construct(
        public array $data,
    ) {}
}

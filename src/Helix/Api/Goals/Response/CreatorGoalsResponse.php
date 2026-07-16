<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Goals\Response;

use SimplyStream\TwitchApi\Helix\Models\Goals\CreatorGoal;

final readonly class CreatorGoalsResponse
{
    /** @param list<CreatorGoal> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

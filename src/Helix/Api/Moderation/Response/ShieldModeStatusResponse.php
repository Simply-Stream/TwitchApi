<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Response;

use SimplyStream\TwitchApi\Helix\Models\Moderation\ShieldModeStatus;

final readonly class ShieldModeStatusResponse
{
    /** @param list<ShieldModeStatus> $data */
    public function __construct(
        public array $data,
    ) {}
}

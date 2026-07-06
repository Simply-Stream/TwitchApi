<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Response;

use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModStatus;

final readonly class AutoModStatusResponse
{
    /** @param list<AutoModStatus> $data */
    public function __construct(
        public array $data,
    ) {}
}

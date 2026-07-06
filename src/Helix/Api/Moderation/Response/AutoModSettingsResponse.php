<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Response;

use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModSettings;

final readonly class AutoModSettingsResponse
{
    /** @param list<AutoModSettings> $data */
    public function __construct(
        public array $data,
    ) {}
}

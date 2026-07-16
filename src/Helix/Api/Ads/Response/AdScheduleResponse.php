<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Ads\Response;

use SimplyStream\TwitchApi\Helix\Models\Ads\AdSchedule;

final readonly class AdScheduleResponse
{
    /** @param list<AdSchedule> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

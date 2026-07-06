<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Schedule\ChannelStreamSchedule;

final readonly class ChannelStreamScheduleResponse
{
    public function __construct(
        public ChannelStreamSchedule $data,
        public ?Pagination $pagination = null,
    ) {}
}

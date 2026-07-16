<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Response;

use SimplyStream\TwitchApi\Helix\Models\Schedule\ChannelStreamSchedule;

final readonly class StreamScheduleSegmentResponse
{
    public function __construct(
        public ChannelStreamSchedule $data,
    ) {
    }
}

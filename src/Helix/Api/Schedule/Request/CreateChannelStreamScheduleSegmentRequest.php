<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Request;

use SimplyStream\TwitchApi\Helix\Models\Schedule\CreateChannelStreamScheduleSegment;

final readonly class CreateChannelStreamScheduleSegmentRequest
{
    /**
     * @param string                             $broadcasterId The ID of the broadcaster that owns the schedule to add
     *                                                          the broadcast segment to. This ID must match the user ID
     *                                                          in the user access token.
     * @param CreateChannelStreamScheduleSegment $segment       The broadcast segment to add.
     */
    public function __construct(
        public string $broadcasterId,
        public CreateChannelStreamScheduleSegment $segment,
    ) {}
}

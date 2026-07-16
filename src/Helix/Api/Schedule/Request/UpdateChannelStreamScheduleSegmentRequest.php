<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Request;

use SimplyStream\TwitchApi\Helix\Models\Schedule\UpdateChannelStreamScheduleSegment;

final readonly class UpdateChannelStreamScheduleSegmentRequest
{
    /**
     * @param string                             $broadcasterId The ID of the broadcaster who owns the broadcast segment
     *                                                          to update. This ID must match the user ID in the user
     *                                                          access token.
     * @param string                             $id            The ID of the broadcast segment to update.
     * @param UpdateChannelStreamScheduleSegment $segment       The fields to update.
     */
    public function __construct(
        public string $broadcasterId,
        public string $id,
        public UpdateChannelStreamScheduleSegment $segment,
    ) {
    }
}

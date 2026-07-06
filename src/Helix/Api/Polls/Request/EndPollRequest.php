<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Polls\Request;

use SimplyStream\TwitchApi\Helix\Models\Polls\EndPoll;

final readonly class EndPollRequest
{
    /**
     * @param EndPoll $poll The poll to end (poll id, broadcaster id and target status).
     */
    public function __construct(
        public EndPoll $poll,
    ) {}
}

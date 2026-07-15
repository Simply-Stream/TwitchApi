<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Polls\Request;

use SimplyStream\TwitchApi\Helix\Models\Polls\CreatePoll;

final readonly class CreatePollRequest
{
    /**
     * @param CreatePoll $poll The poll to create.
     */
    public function __construct(
        public CreatePoll $poll,
    ) {
    }
}

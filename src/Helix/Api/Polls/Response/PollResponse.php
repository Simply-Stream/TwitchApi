<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Polls\Response;

use SimplyStream\TwitchApi\Helix\Models\Polls\Poll;

final readonly class PollResponse
{
    /** @param list<Poll> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

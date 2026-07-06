<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Polls\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Polls\Poll;

final readonly class PollsResponse
{
    /** @param list<Poll> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {}
}

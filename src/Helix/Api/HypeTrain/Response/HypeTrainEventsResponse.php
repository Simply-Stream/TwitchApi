<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\HypeTrain\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\HypeTrainEvent;

final readonly class HypeTrainEventsResponse
{
    /** @param list<HypeTrainEvent> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {}
}

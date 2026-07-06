<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub\Response;

use SimplyStream\TwitchApi\Helix\Api\EventSub\EventSubSubscription;
use SimplyStream\TwitchApi\Helix\Api\Pagination;

final readonly class EventSubSubscriptionsResponse
{
    /** @param list<EventSubSubscription> $data */
    public function __construct(
        public array $data,
        public int $total,
        public int $totalCost,
        public int $maxTotalCost,
        public ?Pagination $pagination = null,
    ) {
    }
}

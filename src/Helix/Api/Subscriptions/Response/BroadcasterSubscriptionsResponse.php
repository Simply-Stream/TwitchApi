<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Subscriptions\Subscription;

final readonly class BroadcasterSubscriptionsResponse
{
    /**
     * @param list<Subscription> $data
     * @param int                $total  The total number of users that subscribe to this broadcaster.
     * @param int                $points The current number of subscriber points earned by this broadcaster. Points are
     *                                  based on the subscription tier of each user that subscribes to this broadcaster.
     */
    public function __construct(
        public array $data,
        public int $total,
        public int $points,
        public ?Pagination $pagination = null,
    ) {}
}

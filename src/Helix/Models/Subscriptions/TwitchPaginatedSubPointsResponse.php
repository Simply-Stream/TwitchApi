<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Subscriptions;

use SimplyStream\TwitchApi\Helix\Models\Pagination;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

/**
 * @template T
 * @extends TwitchPaginatedDataResponse<T>
 */
readonly class TwitchPaginatedSubPointsResponse extends TwitchPaginatedDataResponse
{
    /**
     * @param T               $data
     * @param int             $points The current number of subscriber points earned by this broadcaster. Points are based
     *                                on the subscription tier of each user that subscribes to this broadcaster. For
     *                                example, a Tier 1 subscription is worth 1 point, Tier 2 is worth 2 points, and Tier 3
     *                                is worth 6 points. The number of points determines the number of emote slots that are
     *                                unlocked for the broadcaster (see Subscriber Emote Slots).
     * @param Pagination|null $pagination
     * @param int|null        $total
     */
    public function __construct(
        mixed $data,
        private int $points,
        ?Pagination $pagination = null,
        ?int $total = null
    ) {
        parent::__construct($data, $pagination, $total);
    }

    public function getPoints(): int
    {
        return $this->points;
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Analytics\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Analytics\GameAnalytics;

final readonly class GameAnalyticsResponse
{
    /** @param list<GameAnalytics> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

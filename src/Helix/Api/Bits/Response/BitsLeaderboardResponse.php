<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Bits\Response;

use SimplyStream\TwitchApi\Helix\Api\DateRange;
use SimplyStream\TwitchApi\Helix\Models\Bits\BitsLeaderboard;

final readonly class BitsLeaderboardResponse
{
    /** @param list<BitsLeaderboard> $data */
    public function __construct(
        public array $data,
        public DateRange $dateRange,
        public int $total,
    ) {
    }
}

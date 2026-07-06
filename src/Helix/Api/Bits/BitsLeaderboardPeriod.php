<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Bits;

enum BitsLeaderboardPeriod: string
{
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
    case All = 'all';
}

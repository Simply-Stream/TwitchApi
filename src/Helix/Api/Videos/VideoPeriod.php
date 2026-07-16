<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Videos;

enum VideoPeriod: string
{
    case All = 'all';
    case Day = 'day';
    case Month = 'month';
    case Week = 'week';
}

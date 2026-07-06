<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Videos;

enum VideoSort: string
{
    case Time = 'time';
    case Trending = 'trending';
    case Views = 'views';
}

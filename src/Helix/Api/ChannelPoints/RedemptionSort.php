<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints;

enum RedemptionSort: string
{
    case Oldest = 'OLDEST';
    case Newest = 'NEWEST';
}

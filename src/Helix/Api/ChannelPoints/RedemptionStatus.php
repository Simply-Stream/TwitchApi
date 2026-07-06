<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints;

enum RedemptionStatus: string
{
    case Canceled = 'CANCELED';
    case Fulfilled = 'FULFILLED';
    case Unfulfilled = 'UNFULFILLED';
}

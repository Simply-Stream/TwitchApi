<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Entitlements;

enum FulfillmentStatus: string
{
    case Claimed = 'CLAIMED';
    case Fulfilled = 'FULFILLED';
}

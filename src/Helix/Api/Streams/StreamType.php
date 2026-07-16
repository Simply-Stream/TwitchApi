<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams;

enum StreamType: string
{
    case All = 'all';
    case Live = 'live';
}

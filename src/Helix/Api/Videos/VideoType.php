<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Videos;

enum VideoType: string
{
    case All = 'all';
    case Archive = 'archive';
    case Highlight = 'highlight';
    case Upload = 'upload';
}

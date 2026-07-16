<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users;

enum BlockReason: string
{
    case Harassment = 'harassment';
    case Spam = 'spam';
    case Other = 'other';
}

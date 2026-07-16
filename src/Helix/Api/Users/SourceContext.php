<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users;

enum SourceContext: string
{
    case Chat = 'chat';
    case Whisper = 'whisper';
}

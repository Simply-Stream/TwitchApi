<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

abstract readonly class Event implements EventInterface
{
    use SerializesModels;
}

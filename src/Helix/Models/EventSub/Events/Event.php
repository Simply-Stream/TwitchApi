<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\EventSub\Events;

use SimplyStream\TwitchApiBundle\Helix\Models\SerializesModels;

abstract readonly class Event implements EventInterface
{
    use SerializesModels;
}

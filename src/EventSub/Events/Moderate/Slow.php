<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\Moderate;

final readonly class Slow
{
    /** @param int $waitTimeSeconds The amount of time, in seconds, users must wait between messages. */
    public function __construct(public int $waitTimeSeconds)
    {
    }
}

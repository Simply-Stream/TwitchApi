<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Clock;

final class SystemClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}

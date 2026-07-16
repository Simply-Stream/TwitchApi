<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Clock;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}

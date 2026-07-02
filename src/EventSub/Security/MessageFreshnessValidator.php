<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Security;

use SimplyStream\TwitchApi\EventSub\Clock\ClockInterface;

final readonly class MessageFreshnessValidator
{
    public function __construct(
        private ClockInterface $clock,
        private int $toleranceSeconds = 600,
    ) {
    }

    public function isFresh(\DateTimeImmutable $messageTimestamp): bool
    {
        $age = $this->clock->now()->getTimestamp() - $messageTimestamp->getTimestamp();

        return abs($age) <= $this->toleranceSeconds;
    }
}

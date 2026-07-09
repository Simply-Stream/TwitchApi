<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use DateTimeImmutable;

final readonly class DateRange
{
    public function __construct(
        public ?DateTimeImmutable $startedAt,
        public ?DateTimeImmutable $endedAt,
    ) {
    }
}

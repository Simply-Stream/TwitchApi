<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub;

final readonly class Subscription
{
    public function __construct(
        public string $id,
        public string $status,
        public string $type,
        public string $version,
        public int $cost,
        public ConditionInterface $condition,
        public Transport $transport,
        public \DateTimeImmutable $createdAt,
    ) {
    }
}

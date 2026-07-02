<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Dedup;

final class InMemoryProcessedMessageStore implements ProcessedMessageStore
{
    /** @var array<string, true> */
    private array $ids = [];

    public function contains(string $messageId): bool
    {
        return isset($this->ids[$messageId]);
    }

    public function remember(string $messageId): void
    {
        $this->ids[$messageId] = true;
    }
}

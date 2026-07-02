<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Dedup;

interface ProcessedMessageStore
{
    public function contains(string $messageId): bool;

    public function remember(string $messageId): void;
}

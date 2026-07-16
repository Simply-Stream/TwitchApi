<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

final readonly class ChatBadge
{
    /**
     * @param string      $setId    An ID that identifies this set of chat badges. For example, Bits or Subscriber.
     * @param list<Version> $versions The list of chat badges in this set.
     */
    public function __construct(
        public string $setId,
        public array $versions,
    ) {
    }
}

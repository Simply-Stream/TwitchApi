<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Chat;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ChatBadge
{
    use SerializesModels;

    /**
     * @param string    $setId    An ID that identifies this set of chat badges. For example, Bits or Subscriber.
     * @param Version[] $versions The list of chat badges in this set.
     */
    public function __construct(
        private string $setId,
        private array $versions
    ) {
    }

    public function getSetId(): string
    {
        return $this->setId;
    }

    public function getVersions(): array
    {
        return $this->versions;
    }
}

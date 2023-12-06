<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Channels;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ContentClassificationLabel
{
    use SerializesModels;

    /**
     * @param Label[] $contentClassificationLabels
     */
    public function __construct(
        private array $contentClassificationLabels
    ) {
    }

    public function getContentClassificationLabels(): array
    {
        return $this->contentClassificationLabels;
    }
}

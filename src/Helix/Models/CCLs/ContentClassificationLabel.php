<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\CCLs;

final readonly class ContentClassificationLabel
{
    /**
     * @param string $id          Unique identifier for the CCL.
     * @param string $description Localized description of the CCL.
     * @param string $name        Localized name of the CCL.
     */
    public function __construct(
        public string $id,
        public string $description,
        public string $name,
    ) {
    }
}

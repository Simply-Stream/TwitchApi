<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ContentClassification\Response;

use SimplyStream\TwitchApi\Helix\Models\CCLs\ContentClassificationLabel;

final readonly class ContentClassificationLabelsResponse
{
    /** @param list<ContentClassificationLabel> $data */
    public function __construct(
        public array $data,
    ) {}
}

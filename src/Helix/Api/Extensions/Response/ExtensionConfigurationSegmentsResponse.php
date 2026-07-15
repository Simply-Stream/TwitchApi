<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Response;

use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionConfigurationSegment;

final readonly class ExtensionConfigurationSegmentsResponse
{
    /** @param list<ExtensionConfigurationSegment> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

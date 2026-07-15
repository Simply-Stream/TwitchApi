<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Response;

use SimplyStream\TwitchApi\Helix\Models\Extensions\Extension;

final readonly class ExtensionsResponse
{
    /** @param list<Extension> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

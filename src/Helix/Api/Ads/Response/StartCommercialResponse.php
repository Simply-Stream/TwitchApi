<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Ads\Response;

use SimplyStream\TwitchApi\Helix\Models\Ads\Commercial;

final readonly class StartCommercialResponse
{
    /** @param list<Commercial> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

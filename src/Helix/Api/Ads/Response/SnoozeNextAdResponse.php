<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Ads\Response;

use SimplyStream\TwitchApi\Helix\Models\Ads\SnoozeNextAd;

final readonly class SnoozeNextAdResponse
{
    /** @param list<SnoozeNextAd> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Charity\Response;

use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaign;

final readonly class CharityCampaignResponse
{
    /** @param list<CharityCampaign> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

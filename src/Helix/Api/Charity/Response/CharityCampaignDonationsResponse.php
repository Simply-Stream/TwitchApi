<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Charity\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityCampaignDonation;

final readonly class CharityCampaignDonationsResponse
{
    /** @param list<CharityCampaignDonation> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

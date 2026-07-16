<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomRewardRedemption;

final readonly class CustomRewardRedemptionResponse
{
    /** @param list<CustomRewardRedemption> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

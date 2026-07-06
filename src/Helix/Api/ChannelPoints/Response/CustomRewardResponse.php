<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response;

use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomReward;

final readonly class CustomRewardResponse
{
    /** @param list<CustomReward> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

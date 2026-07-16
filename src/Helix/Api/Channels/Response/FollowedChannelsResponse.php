<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Channels\FollowedChannel;

final readonly class FollowedChannelsResponse
{
    /** @param list<FollowedChannel> $data */
    public function __construct(
        public array $data,
        public int $total,
        public ?Pagination $pagination = null,
    ) {
    }
}

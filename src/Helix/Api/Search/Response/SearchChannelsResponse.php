<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Search\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Search\Channel;

final readonly class SearchChannelsResponse
{
    /** @param list<Channel> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

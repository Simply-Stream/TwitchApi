<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionLiveChannel;

final readonly class ExtensionLiveChannelsResponse
{
    /** @param list<ExtensionLiveChannel> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

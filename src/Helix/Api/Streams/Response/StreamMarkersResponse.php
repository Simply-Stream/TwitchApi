<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Streams\StreamMarker;

final readonly class StreamMarkersResponse
{
    /** @param list<StreamMarker> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {}
}

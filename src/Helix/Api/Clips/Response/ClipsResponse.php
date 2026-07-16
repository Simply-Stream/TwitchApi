<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Clips\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Clip\Clip;

final readonly class ClipsResponse
{
    /** @param list<Clip> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

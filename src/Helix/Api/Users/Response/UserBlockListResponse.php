<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Users\UserBlock;

final readonly class UserBlockListResponse
{
    /** @param list<UserBlock> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {}
}

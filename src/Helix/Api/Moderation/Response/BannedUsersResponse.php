<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BannedUser;

final readonly class BannedUsersResponse
{
    /** @param list<BannedUser> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

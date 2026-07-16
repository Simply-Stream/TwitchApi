<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Response;

use SimplyStream\TwitchApi\Helix\Models\Users\User;

final readonly class UsersResponse
{
    /** @param list<User> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

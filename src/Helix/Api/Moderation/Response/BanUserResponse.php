<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Response;

use SimplyStream\TwitchApi\Helix\Models\Moderation\UserBan;

final readonly class BanUserResponse
{
    /** @param list<UserBan> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

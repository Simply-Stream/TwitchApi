<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Response;

use SimplyStream\TwitchApi\Helix\Models\Users\UserExtension;

final readonly class UserExtensionsResponse
{
    /** @param list<UserExtension> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

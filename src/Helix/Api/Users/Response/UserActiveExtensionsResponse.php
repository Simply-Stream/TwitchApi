<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Response;

use SimplyStream\TwitchApi\Helix\Models\Users\UserActiveExtension;

final readonly class UserActiveExtensionsResponse
{
    public function __construct(
        public UserActiveExtension $data,
    ) {}
}

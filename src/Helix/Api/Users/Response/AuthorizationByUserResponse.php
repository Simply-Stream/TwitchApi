<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Response;

use SimplyStream\TwitchApi\Helix\Api\Users\AuthorizationUser;

final readonly class AuthorizationByUserResponse {
    /**
     * @param list<AuthorizationUser> $data List of users and their authorized scopes.
     */
    public function __construct(
        public array $data,
    ) {
    }
}

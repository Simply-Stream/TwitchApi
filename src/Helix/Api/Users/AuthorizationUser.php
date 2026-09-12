<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users;

final readonly class AuthorizationUser {
    /**
     * @param string         $userId The user’s ID.
     * @param string         $userName The user’s display name.
     * @param string         $userLogin The user’s login name.
     * @param array<string>  $scopes An array of all the scopes the user has granted to the client ID.
     * @param bool           $hasAuthorization A boolean indicating whether or not the specified user has authorized this application.
     */
    public function __construct(
        public string $userId,
        public string $userName,
        public string $userLogin,
        public array $scopes,
        public bool $hasAuthorization,
    ) {
    }
}

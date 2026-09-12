<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

final readonly class GetAuthorizationByUserRequest {
    /**
     * @param string $userId The ID of the user(s) you want to check authorization for. To specify more than one user,
     *                       include the user_id parameter for each user to get.
     *                       For example, user_id=1234&user_id=5678. The maximum number of IDs you may specify is 10.
     */
    public function __construct(
        public string $userId,
    ) {
    }
}

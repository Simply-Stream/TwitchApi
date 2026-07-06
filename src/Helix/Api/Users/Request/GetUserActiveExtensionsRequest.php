<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

final readonly class GetUserActiveExtensionsRequest
{
    /**
     * @param string|null $userId The ID of the broadcaster whose active extensions you want to get.
     *
     *                           This parameter is required if you specify an app access token and is optional if you
     *                           specify a user access token. If you specify a user access token and don’t specify this
     *                           parameter, the API uses the user ID from the access token.
     */
    public function __construct(
        public ?string $userId = null,
    ) {}
}

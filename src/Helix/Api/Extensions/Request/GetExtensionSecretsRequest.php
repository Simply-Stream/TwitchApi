<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

final readonly class GetExtensionSecretsRequest
{
    /**
     * @param string $extensionId The ID of the extension whose shared secrets you want to get.
     */
    public function __construct(
        public string $extensionId,
    ) {
    }
}

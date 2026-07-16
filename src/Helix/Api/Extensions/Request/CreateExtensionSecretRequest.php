<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

final readonly class CreateExtensionSecretRequest
{
    /**
     * @param string $extensionId The ID of the extension to apply the shared secret to.
     * @param int    $delay       The amount of time, in seconds, to delay activating the secret. Minimum 300.
     */
    public function __construct(
        public string $extensionId,
        public int $delay = 300,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

final readonly class GetExtensionsRequest
{
    /**
     * @param string      $extensionId      The ID of the extension to get.
     * @param string|null $extensionVersion The version to get. If omitted, returns the latest released version.
     */
    public function __construct(
        public string $extensionId,
        public ?string $extensionVersion = null,
    ) {
    }
}

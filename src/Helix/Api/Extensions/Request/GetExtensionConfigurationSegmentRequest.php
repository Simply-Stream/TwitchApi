<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

final readonly class GetExtensionConfigurationSegmentRequest
{
    /**
     * @param string      $extensionId   The ID of the extension that contains the configuration segment you want to
     *                                   get.
     * @param string[]    $segments      The types of configuration segment to get. Case-sensitive. Possible values
     *                                   are: broadcaster, developer, global. Duplicates are ignored by Twitch.
     * @param string|null $broadcasterId The ID of the broadcaster that installed the extension. Required when a
     *                                   segment is broadcaster or developer; must be omitted for global.
     */
    public function __construct(
        public string $extensionId,
        public array $segments,
        public ?string $broadcasterId = null,
    ) {
    }
}

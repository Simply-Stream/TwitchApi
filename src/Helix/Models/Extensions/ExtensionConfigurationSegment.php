<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

final readonly class ExtensionConfigurationSegment
{
    /**
     * @param string      $segment       The type of segment. Possible values are:
     *                                   - broadcaster
     *                                   - developer
     *                                   - global
     * @param string      $content       The contents of the segment. This string may be a plain-text string or a
     *                                   string-encoded JSON object.
     * @param string      $version       The version number that identifies this definition of the segment’s data.
     * @param string|null $broadcasterId The ID of the broadcaster that installed the extension. The object includes this
     *                                   field only if the segment query parameter is set to developer or broadcaster.
     */
    public function __construct(
        public string $segment,
        public string $content,
        public string $version,
        public ?string $broadcasterId = null,
    ) {
    }
}

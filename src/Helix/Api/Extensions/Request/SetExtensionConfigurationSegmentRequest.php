<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

use Webmozart\Assert\Assert;

final readonly class SetExtensionConfigurationSegmentRequest
{
    /**
     * @param string      $extensionId   The ID of the extension to update.
     * @param string      $segment       The configuration segment to update. Possible case-sensitive values are:
     *                                   - broadcaster
     *                                   - developer
     *                                   - global
     * @param string|null $broadcasterId The ID of the broadcaster that installed the extension. Include this field
     *                                   only if the segment is set to developer or broadcaster.
     * @param string|null $content       The contents of the segment. This string may be a plain-text string or a
     *                                   string-encoded JSON object.
     * @param string|null $version       The version number that identifies this definition of the segment’s data. If
     *                                   not specified, the latest definition is updated.
     */
    public function __construct(
        public string $extensionId,
        public string $segment,
        public ?string $broadcasterId = null,
        public ?string $content = null,
        public ?string $version = null
    ) {
        Assert::stringNotEmpty($this->extensionId, 'Extension id can\'t be empty');
        Assert::inArray(
            $this->segment,
            ['broadcaster', 'developer', 'global'],
            'Segment got an invalid value. Allowed values: %2$s, got %s'
        );
    }
}

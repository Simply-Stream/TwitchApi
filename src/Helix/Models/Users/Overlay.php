<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

final readonly class Overlay
{
    /**
     * @param bool        $active  A Boolean value that determines the extension’s activation state. If false, the user
     *                             has not configured this overlay extension.
     * @param string|null $id      An ID that identifies the extension.
     * @param string|null $version The extension’s version.
     * @param string|null $name    The extension’s name.
     */
    public function __construct(
        public bool $active,
        public ?string $id = null,
        public ?string $version = null,
        public ?string $name = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

final readonly class Component
{
    /**
     * @param bool        $active  A Boolean value that determines the extension’s activation state. If false, the user
     *                             has not configured this component extension.
     * @param string|null $id      An ID that identifies the extension.
     * @param string|null $version The extension’s version.
     * @param string|null $name    The extension’s name.
     * @param int|null    $x       The x-coordinate where the extension is placed.
     * @param int|null    $y       The y-coordinate where the extension is placed.
     */
    public function __construct(
        public bool $active,
        public ?string $id = null,
        public ?string $version = null,
        public ?string $name = null,
        public ?int $x = null,
        public ?int $y = null,
    ) {
    }
}

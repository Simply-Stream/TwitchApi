<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Overlay
{
    use SerializesModels;

    /**
     * @param bool        $active  A Boolean value that determines the extension’s activation state. If false, the user
     *                             has not configured this overlay extension.
     * @param string|null $id      An ID that identifies the extension.
     * @param string|null $version The extension’s version.
     * @param string|null $name    The extension’s name.
     */
    public function __construct(
        private bool $active,
        private ?string $id = null,
        private ?string $version = null,
        private ?string $name = null,
    ) {
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}

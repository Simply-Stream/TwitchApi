<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Users;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class UserExtension
{
    use SerializesModels;

    /**
     * @param string   $id          An ID that identifies the extension.
     * @param string   $version     The extension’s version.
     * @param string   $name        The extension’s name.
     * @param bool     $canActivate A Boolean value that determines whether the extension is configured and can be
     *                              activated. Is true if the extension is configured and can be activated.
     * @param string[] $type        The extension types that you can activate for this extension. Possible values are:
     *                              - component
     *                              - mobile
     *                              - overlay
     *                              - panel
     */
    public function __construct(
        private string $id,
        private string $version,
        private string $name,
        private bool $canActivate,
        private array $type
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function canActivate(): bool
    {
        return $this->canActivate;
    }

    public function getType(): array
    {
        return $this->type;
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ExtensionSecret
{
    use SerializesModels;

    /**
     * @param int      $formatVersion The version number that identifies this definition of the secret’s data.
     * @param Secret[] $secrets       The list of secrets.
     */
    public function __construct(
        private int $formatVersion,
        private array $secrets
    ) {
    }

    public function getFormatVersion(): int
    {
        return $this->formatVersion;
    }

    public function getSecrets(): array
    {
        return $this->secrets;
    }
}

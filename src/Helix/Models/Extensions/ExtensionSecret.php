<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

final readonly class ExtensionSecret
{
    /**
     * @param int      $formatVersion The version number that identifies this definition of the secret’s data.
     * @param Secret[] $secrets       The list of secrets.
     */
    public function __construct(
        public int $formatVersion,
        public array $secrets
    ) {
    }
}

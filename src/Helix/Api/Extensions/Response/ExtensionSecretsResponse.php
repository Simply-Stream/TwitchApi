<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Response;

use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionSecret;

final readonly class ExtensionSecretsResponse
{
    /** @param list<ExtensionSecret> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

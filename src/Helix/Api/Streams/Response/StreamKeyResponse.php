<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Response;

use SimplyStream\TwitchApi\Helix\Models\Streams\StreamKey;

final readonly class StreamKeyResponse
{
    /** @param list<StreamKey> $data */
    public function __construct(
        public array $data,
    ) {}
}

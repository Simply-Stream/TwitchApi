<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Response;

use SimplyStream\TwitchApi\Helix\Models\Streams\Marker;

final readonly class CreateStreamMarkerResponse
{
    /** @param list<Marker> $data */
    public function __construct(
        public array $data,
    ) {}
}

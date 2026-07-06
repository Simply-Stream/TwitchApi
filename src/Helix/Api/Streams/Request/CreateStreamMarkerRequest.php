<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Request;

use SimplyStream\TwitchApi\Helix\Models\Streams\CreateStreamMarker;

final readonly class CreateStreamMarkerRequest
{
    /**
     * @param CreateStreamMarker $marker The marker to add (user id and optional description).
     */
    public function __construct(
        public CreateStreamMarker $marker,
    ) {}
}

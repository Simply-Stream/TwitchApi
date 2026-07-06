<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Clips\Response;

use SimplyStream\TwitchApi\Helix\Models\Clip\ClipProcess;

final readonly class CreateClipResponse
{
    /** @param list<ClipProcess> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

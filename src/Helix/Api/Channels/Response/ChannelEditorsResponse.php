<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Response;

use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelEditor;

final readonly class ChannelEditorsResponse
{
    /** @param list<ChannelEditor> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

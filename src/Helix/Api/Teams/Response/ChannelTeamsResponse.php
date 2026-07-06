<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Teams\Response;

use SimplyStream\TwitchApi\Helix\Models\Teams\ChannelTeam;

final readonly class ChannelTeamsResponse
{
    /** @param list<ChannelTeam> $data */
    public function __construct(
        public array $data,
    ) {}
}

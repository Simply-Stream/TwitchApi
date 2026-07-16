<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Response;

use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelInformation;

final readonly class ChannelInformationResponse
{
    /** @param list<ChannelInformation> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

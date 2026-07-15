<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Response;

use SimplyStream\TwitchApi\Helix\Models\GuestStar\ChannelGuestStarSetting;

final readonly class ChannelGuestStarSettingsResponse
{
    /** @param list<ChannelGuestStarSetting> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

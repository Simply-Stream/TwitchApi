<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\GuestStar\Request;

use SimplyStream\TwitchApi\Helix\Models\GuestStar\UpdateChannelGuestStarSetting;

final readonly class UpdateChannelGuestStarSettingsRequest
{
    /**
     * @param string                        $broadcasterId The ID of the broadcaster you want to update Guest Star
     *                                                     settings for.
     * @param UpdateChannelGuestStarSetting $settings      The settings to update.
     */
    public function __construct(
        public string $broadcasterId,
        public UpdateChannelGuestStarSetting $settings,
    ) {}
}

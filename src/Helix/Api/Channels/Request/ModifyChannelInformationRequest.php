<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Request;

use SimplyStream\TwitchApi\Helix\Models\Channels\ModifyChannelInformation;

final readonly class ModifyChannelInformationRequest
{
    /**
     * @param string                   $broadcasterId The ID of the broadcaster whose channel you want to update. This
     *                                                ID must match the user ID associated with the user access token.
     * @param ModifyChannelInformation $information   The channel properties to update. See ModifyChannelInformation for
     *                                                properties.
     */
    public function __construct(
        public string $broadcasterId,
        public ModifyChannelInformation $information,
    ) {
    }
}

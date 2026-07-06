<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request;

use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CreateCustomReward;

final readonly class CreateCustomRewardRequest
{
    /**
     * @param string             $broadcasterId The ID of the broadcaster to add the custom reward to. This ID must
     *                                          match the user ID found in the OAuth token.
     * @param CreateCustomReward $reward        The custom reward to create.
     */
    public function __construct(
        public string $broadcasterId,
        public CreateCustomReward $reward,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request;

use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CreateCustomReward;

final readonly class UpdateCustomRewardRequest
{
    /**
     * @param string             $broadcasterId The ID of the broadcaster that’s updating the reward. This ID must
     *                                          match the user ID found in the OAuth token.
     * @param string             $id            The ID of the reward to update.
     * @param CreateCustomReward $reward        The fields to update.
     */
    public function __construct(
        public string $broadcasterId,
        public string $id,
        public CreateCustomReward $reward,
    ) {
    }
}

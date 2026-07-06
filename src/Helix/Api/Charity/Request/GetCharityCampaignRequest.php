<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Charity\Request;

final readonly class GetCharityCampaignRequest
{
    /**
     * @param string $broadcasterId The ID of the broadcaster that’s currently running a charity campaign. This ID must
     *                             match the user ID in the access token.
     */
    public function __construct(
        public string $broadcasterId,
    ) {
    }
}

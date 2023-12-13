<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Goals\CreatorGoal;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class GoalsApi extends AbstractApi
{
    protected const BASE_PATH = 'goals';

    /**
     * Gets the broadcaster’s list of active goals. Use this endpoint to get the current progress of each goal.
     *
     * Instead of polling for the progress of a goal, consider subscribing to receive notifications when a goal makes
     * progress using the channel.goal.progress subscription type. Read More
     *
     * Authorization
     * Requires a user access token that includes the channel:read:goals scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/goals
     *
     * @param string               $broadcasterId      The ID of the broadcaster that created the goals. This ID must
     *                                                 match the user ID in the user access token.
     * @param AccessTokenInterface $accessToken        Requires a user access token that includes the
     *                                                 channel:read:goals scope.
     *
     * @return TwitchDataResponse<CreatorGoal[]>
     */
    public function getCreatorGoals(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, CreatorGoal::class),
            accessToken: $accessToken
        );
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Goals\Request\GetCreatorGoalsRequest;
use SimplyStream\TwitchApi\Helix\Api\Goals\Response\CreatorGoalsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class GoalsApi extends AbstractApi
{
    private const string BASE_PATH = 'goals';

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
     * @param AccessTokenInterface   $accessToken Requires a user access token that includes the channel:read:goals
     *                                            scope.
     */
    public function getCreatorGoals(
        GetCreatorGoalsRequest $request,
        AccessTokenInterface $accessToken,
    ): CreatorGoalsResponse {
        return $this->get(
            self::BASE_PATH,
            CreatorGoalsResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }
}

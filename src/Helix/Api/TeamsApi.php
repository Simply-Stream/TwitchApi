<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Teams\Request\GetChannelTeamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Teams\Request\GetTeamsRequest;
use SimplyStream\TwitchApi\Helix\Api\Teams\Response\ChannelTeamsResponse;
use SimplyStream\TwitchApi\Helix\Api\Teams\Response\TeamsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class TeamsApi extends AbstractApi
{
    private const string BASE_PATH = 'teams';

    /**
     * Gets the list of Twitch teams that the broadcaster is a member of.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/teams/channel
     *
     * @param GetChannelTeamsRequest $request
     * @param AccessTokenInterface   $accessToken Requires an app access token or user access token.
     *
     * @return ChannelTeamsResponse
     */
    public function getChannelTeams(
        GetChannelTeamsRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelTeamsResponse {
        return $this->get(
            self::BASE_PATH . '/channel',
            ChannelTeamsResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
        );
    }

    /**
     * Gets information about the specified Twitch team. Read More
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/teams
     *
     * @param GetTeamsRequest      $request
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     *
     * @return TeamsResponse
     */
    public function getTeams(
        GetTeamsRequest $request,
        AccessTokenInterface $accessToken,
    ): TeamsResponse {
        $query = array_filter(
            [
                'name' => $request->name,
                'id'   => $request->id,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH, TeamsResponse::class, $accessToken, $query);
    }
}

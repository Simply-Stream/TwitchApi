<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Teams\ChannelTeam;
use SimplyStream\TwitchApi\Helix\Models\Teams\Team;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class TeamsApi extends AbstractApi
{
    protected const BASE_PATH = 'teams';

    /**
     * Gets the list of Twitch teams that the broadcaster is a member of.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/teams/channel
     *
     * @param string               $broadcasterId The ID of the broadcaster whose teams you want to get.
     * @param AccessTokenInterface $accessToken   Requires an app access token or user access token.
     *
     * @return TwitchDataResponse<ChannelTeam[]>
     * @throws JsonException
     */
    public function getChannelTeams(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/channel',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ChannelTeam::class),
            accessToken: $accessToken
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
     * @param AccessTokenInterface $accessToken      Requires an app access token or user access token.
     * @param string|null          $name             The name of the team to get. This parameter and the id parameter
     *                                               are mutually exclusive; you must specify the team’s name or ID but
     *                                               not both.
     * @param string|null          $id               The ID of the team to get. This parameter and the name parameter
     *                                               are mutually exclusive; you must specify the team’s name or ID but
     *                                               not both.
     *
     * @return TwitchDataResponse<Team[]>
     */
    public function getTeams(
        AccessTokenInterface $accessToken,
        ?string $name = null,
        ?string $id = null,
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'name' => $name,
                'id' => $id,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Team::class),
            accessToken: $accessToken
        );
    }
}

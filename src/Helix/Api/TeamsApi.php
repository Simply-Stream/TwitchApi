<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Teams\Team;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;

class TeamsApi extends AbstractApi
{
    protected const BASE_PATH = 'teams';

    /**
     * Gets the list of Twitch teams that the broadcaster is a member of.
     *
     * Authentication:
     * Requires an app access token or user access token.
     *
     * @param string                    $broadcasterId The ID of the broadcaster whose teams you want to get.
     * @param AccessTokenInterface|null $accessToken
     *
     * @return TwitchDataResponse<Team[]>
     * @throws JsonException
     */
    public function getChannelTeams(
        string $broadcasterId,
        AccessTokenInterface $accessToken = null
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/channel',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, Team::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets information about the specified Twitch team.
     *
     * Authentication:
     * Requires an app access token or user access token.
     *
     * @param string                    $name        The name of the team to get. This parameter and the id parameter
     *                                               are mutually exclusive; you must specify the team’s name or ID but
     *                                               not both.
     * @param string                    $id          The ID of the team to get. This parameter and the name parameter
     *                                               are mutually exclusive; you must specify the team’s name or ID but
     *                                               not both.
     * @param AccessTokenInterface|null $accessToken
     *
     * @return TwitchDataResponse<Team[]>
     * @throws JsonException
     */
    public function getTeams(
        string $name,
        string $id,
        AccessTokenInterface $accessToken = null
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

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Games\Request\GetGamesRequest;
use SimplyStream\TwitchApi\Helix\Api\Games\Request\GetTopGamesRequest;
use SimplyStream\TwitchApi\Helix\Api\Games\Response\GamesResponse;
use SimplyStream\TwitchApi\Helix\Api\Games\Response\TopGamesResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class GamesApi extends AbstractApi
{
    /**
     * Gets information about all broadcasts on Twitch.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/games/top
     *
     * @param GetTopGamesRequest   $request
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     *
     * @return TopGamesResponse
     */
    public function getTopGames(
        GetTopGamesRequest $request,
        AccessTokenInterface $accessToken,
    ): TopGamesResponse {
        $query = array_filter(
            [
                'after'  => $request->after,
                'before' => $request->before,
                'first'  => $request->first,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get('games/top', TopGamesResponse::class, $accessToken, $query);
    }

    /**
     * Gets information about specified categories or games.
     *
     * You may get up to 100 categories or games by specifying their ID or name. You may specify all IDs, all names, or
     * a combination of IDs and names. If you specify a combination of IDs and names, the total number of IDs and names
     * must not exceed 100.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/games
     *
     * @param GetGamesRequest      $request
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     *
     * @return GamesResponse
     */
    public function getGames(
        GetGamesRequest $request,
        AccessTokenInterface $accessToken,
    ): GamesResponse {
        $query = array_filter(
            [
                'id'      => $request->ids,
                'name'    => $request->names,
                'igdb_id' => $request->igdbIds,
            ],
            static fn (mixed $v): bool => $v !== [],
        );

        return $this->get('games', GamesResponse::class, $accessToken, $query);
    }
}

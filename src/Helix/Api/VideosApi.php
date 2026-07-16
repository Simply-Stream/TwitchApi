<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Videos\Request\DeleteVideosRequest;
use SimplyStream\TwitchApi\Helix\Api\Videos\Request\GetVideosRequest;
use SimplyStream\TwitchApi\Helix\Api\Videos\Response\DeleteVideosResponse;
use SimplyStream\TwitchApi\Helix\Api\Videos\Response\VideosResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class VideosApi extends AbstractApi
{
    private const string BASE_PATH = 'videos';

    /**
     * Gets information about one or more published videos. You may get videos by ID, by user, or by game/category.
     *
     * You may apply several filters to get a subset of the videos. The filters are applied as an AND operation to each
     * video. For example, if language is set to ‘de’ and game_id is set to 21779, the response includes only videos
     * that show playing League of Legends by users that stream in German. The filters apply only if you get videos by
     * user ID or game ID.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/videos
     *
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     */
    public function getVideos(
        GetVideosRequest $request,
        AccessTokenInterface $accessToken,
    ): VideosResponse {
        $query = array_filter(
            [
                'id'       => $request->ids,
                'user_id'  => $request->userId,
                'game_id'  => $request->gameId,
                'language' => $request->language,
                'period'   => $request->period->value,
                'sort'     => $request->sort->value,
                'type'     => $request->type->value,
                'first'    => $request->first,
                'after'    => $request->after,
                'before'   => $request->before,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH, VideosResponse::class, $accessToken, $query);
    }

    /**
     * Deletes one or more videos. You may delete past broadcasts, highlights, or uploads.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:videos scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/videos
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:videos
     *                                          scope.
     */
    public function deleteVideos(
        DeleteVideosRequest $request,
        AccessTokenInterface $accessToken,
    ): DeleteVideosResponse {
        return $this->deleteWithResponse(
            self::BASE_PATH,
            DeleteVideosResponse::class,
            $accessToken,
            [
                'id' => $request->ids,
            ],
        );
    }
}

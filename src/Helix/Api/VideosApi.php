<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApi\Helix\Models\Videos\Video;

class VideosApi extends AbstractApi
{
    protected const BASE_PATH = 'videos';

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
     * @param AccessTokenInterface $accessToken   Requires an app access token or user access token.
     * @param string[]             $id            A list of IDs that identify the videos you want to get. To get more
     *                                            than one video, include this parameter for each video you want to
     *                                            get. For example, id=1234&id=5678. You may specify a maximum of 100
     *                                            IDs. The endpoint ignores duplicate IDs and IDs that weren’t found
     *                                            (if there’s at least one valid ID).
     *
     *                                            The id, user_id, and game_id parameters are mutually exclusive.
     * @param string|null          $userId        The ID of the user whose list of videos you want to get.
     *
     *                                            The id, user_id, and game_id parameters are mutually exclusive.
     * @param string|null          $gameId        A category or game ID. The response contains a maximum of 500 videos
     *                                            that show this content. To get category/game IDs, use the Search
     *                                            Categories endpoint.
     *
     *                                            The id, user_id, and game_id parameters are mutually exclusive.
     * @param string|null          $language      A filter used to filter the list of videos by the language that the
     *                                            video owner broadcasts in. For example, to get videos that were
     *                                            broadcast in German, set this parameter to the ISO 639-1 two-letter
     *                                            code for German (i.e., DE). For a list of supported languages, see
     *                                            Supported Stream Language. If the language is not supported, use
     *                                            “other.”
     *
     *                                            Specify this parameter only if you specify the game_id query
     *                                            parameter.
     * @param string               $period        A filter used to filter the list of videos by when they were
     *                                            published. For example, videos published in the last week. Possible
     *                                            values are:
     *                                            - all
     *                                            - day
     *                                            - month
     *                                            - week
     *                                            The default is “all,” which returns videos published in all periods.
     *
     *                                            Specify this parameter only if you specify the game_id or user_id
     *                                            query parameter.
     * @param string               $sort          The order to sort the returned videos in. Possible values are:
     *                                            - time — Sort the results in descending order by when they were
     *                                            created (i.e., latest video first).
     *                                            - trending — Sort the results in descending order by biggest gains in
     *                                            viewership (i.e., highest trending video first).
     *                                            - views — Sort the results in descending order by most views (i.e.,
     *                                            highest number of views first). The default is “time.”
     *
     *                                            Specify this parameter only if you specify the game_id or user_id
     *                                            query parameter.
     * @param string               $type          A filter used to filter the list of videos by the video’s type.
     *                                            Possible case-sensitive values are:
     *                                            - all
     *                                            - archive — On-demand videos (VODs) of past streams.
     *                                            - highlight — Highlight reels of past streams.
     *                                            - upload — External videos that the broadcaster uploaded using the
     *                                            Video Producer. The default is “all,” which returns all video types.
     *
     *                                            Specify this parameter only if you specify the game_id or user_id
     *                                            query parameter.
     * @param int                  $first         The maximum number of items to return per page in the response. The
     *                                            minimum page size is
     *                                            1 item per page and the maximum is 100. The default is 20.
     *
     *                                            Specify this parameter only if you specify the game_id or user_id
     *                                            query parameter.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     *
     *                                            Specify this parameter only if you specify the user_id query
     *                                            parameter.
     * @param string|null          $before        The cursor used to get the previous page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     *
     *                                            Specify this parameter only if you specify the user_id query
     *                                            parameter.
     *
     * @return TwitchPaginatedDataResponse<Video[]>
     */
    public function getVideos(
        AccessTokenInterface $accessToken,
        array $id = null,
        string $userId = null,
        string $gameId = null,
        string $language = null,
        string $period = 'all',
        string $sort = 'time',
        string $type = 'all',
        int $first = 20,
        string $after = null,
        string $before = null,
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'id' => $id,
                'user_id' => $userId,
                'game_id' => $gameId,
                'language' => $language,
                'period' => $period,
                'sort' => $sort,
                'type' => $type,
                'first' => $first,
                'after' => $after,
                'before' => $before,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, Video::class),
            accessToken: $accessToken
        );
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
     * @param string               $id          The list of videos to delete. To specify more than one video, include
     *                                          the id parameter for each video to delete. For example,
     *                                          id=1234&id=5678. You can delete a maximum of 5 videos per request.
     *                                          Ignores invalid video IDs.
     *
     *                                 If the user doesn’t have permission to delete one of the videos in the list,
     *                                 none of the videos are deleted.
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the channel:manage:videos
     *                                          scope.
     *
     * @return TwitchDataResponse<string[]>
     */
    public function deleteVideos(
        string $id,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'id' => $id,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, 'string'),
            method: 'DELETE',
            accessToken: $accessToken
        );
    }
}

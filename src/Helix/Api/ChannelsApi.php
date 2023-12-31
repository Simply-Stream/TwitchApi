<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelEditor;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelFollow;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelInformation;
use SimplyStream\TwitchApi\Helix\Models\Channels\FollowedChannel;
use SimplyStream\TwitchApi\Helix\Models\Channels\ModifyChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class ChannelsApi extends AbstractApi
{
    protected const BASE_PATH = 'channels';

    /**
     * Gets information about one or more channels.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/channels
     *
     * @param array                $broadcasterId      The ID of the broadcaster whose channel you want to get. To
     *                                                 specify more than one ID, include this parameter for each
     *                                                 broadcaster you want to get. For example,
     *                                                 broadcaster_id=1234&broadcaster_id=5678. You may specify a
     *                                                 maximum of 100 IDs. The API ignores duplicate IDs and IDs that
     *                                                 are not found.
     * @param AccessTokenInterface $accessToken        Requires an app access token or user access token.
     *
     * @return TwitchDataResponse<ChannelInformation[]>
     */
    public function getChannelInformation(
        array $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ChannelInformation::class),
            accessToken: $accessToken
        );
    }

    /**
     * Updates a channel’s properties.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:broadcast scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/channels
     *
     * @param string                          $broadcasterId The ID of the broadcaster whose channel you want to
     *                                                       update. This ID must match the user ID associated with the
     *                                                       user access token.
     * @param ModifyChannelInformationRequest $body          See ModifyChannelInformationRequest::class for properties
     * @param AccessTokenInterface            $accessToken   Requires a user access token that includes the
     *                                                       channel:manage:broadcast scope.
     *
     * @return void
     */
    public function modifyChannelInformation(
        string $broadcasterId,
        ModifyChannelInformationRequest $body,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            method: 'PATCH',
            body: $body,
            accessToken: $accessToken,
        );
    }

    /**
     * Gets the broadcaster’s list editors.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:editors scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/channels/editors
     *
     * @param string               $broadcasterId      The ID of the broadcaster that owns the channel. This ID must
     *                                                 match the user ID in the access token.
     * @param AccessTokenInterface $accessToken        Requires a user access token that includes the
     *                                                 channel:read:editors scope.
     *
     * @return TwitchDataResponse<ChannelEditor[]>
     */
    public function getChannelEditors(
        string $broadcasterId,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/editors',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, ChannelEditor::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets a list of broadcasters that the specified user follows. You can also use this endpoint to see whether a
     * user follows a specific broadcaster.
     *
     * Authorization
     * Requires a user access token that includes the user:read:follows scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/channels/followed
     *
     * @param string               $userId        A user’s ID. Returns the list of broadcasters that this user follows.
     *                                            This ID must match the user ID in the user OAuth token.
     * @param string|null          $broadcasterId A broadcaster’s ID. Use this parameter to see whether the user
     *                                            follows this broadcaster. If specified, the response contains this
     *                                            broadcaster if the user follows them. If not specified, the response
     *                                            contains all broadcasters that the user follows.
     * @param int                  $first         The maximum number of items to return per page in the response. The
     *                                            minimum page size is
     *                                            1 item per page and the maximum is 100. The default is 20.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the user:read:follows
     *                                            scope.
     *
     * @return TwitchPaginatedDataResponse<FollowedChannel[]>
     */
    public function getFollowedChannels(
        string $userId,
        AccessTokenInterface $accessToken,
        ?string $broadcasterId = null,
        int $first = 20,
        ?string $after = null
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/followed',
            query: [
                'user_id' => $userId,
                'broadcaster_id' => $broadcasterId,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, FollowedChannel::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets a list of users that follow the specified broadcaster. You can also use this endpoint to see whether a
     * specific user follows the broadcaster.
     *
     * Authorization
     * - Requires a user access token that includes the moderator:read:followers scope.
     * - The ID in the broadcaster_id query parameter must match the user ID in the access token or the user ID in the
     *   access token must be a moderator for the specified broadcaster.
     *
     * This endpoint will return specific follower information only if both of the above are true. If a scope is not
     * provided or the user isn’t the broadcaster or a moderator for the specified channel, only the total follower
     * count will be included in the response.
     *
     * URL
     * GET https://api.twitch.tv/helix/channels/followers
     *
     * @param string               $broadcasterId The broadcaster’s ID. Returns the list of users that follow this
     *                                            broadcaster.
     * @param string|null          $userId        A user’s ID. Use this parameter to see whether the user follows this
     *                                            broadcaster. If specified, the response contains this user if they
     *                                            follow the broadcaster. If not specified, the response contains all
     *                                            users that follow the broadcaster.
     * @param int                  $first         The maximum number of items to return per page in the response. The
     *                                            minimum page size is
     *                                            1 item per page and the maximum is 100. The default is 20.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the
     *                                            moderator:read:followers scope.
     *
     * @return TwitchPaginatedDataResponse<ChannelFollow[]>
     * @throws JsonException
     *
     * @see https://dev.twitch.tv/docs/api/guide#pagination Pagination
     */
    public function getChannelFollowers(
        string $broadcasterId,
        AccessTokenInterface $accessToken,
        ?string $userId = null,
        int $first = 20,
        ?string $after = null
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/followers',
            query: [
                'user_id' => $userId,
                'broadcaster_id' => $broadcasterId,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, ChannelFollow::class),
            accessToken: $accessToken
        );
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelEditorsRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelFollowersRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\GetFollowedChannelsRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Request\ModifyChannelInformationRequest;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelEditorsResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelFollowersResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\ChannelInformationResponse;
use SimplyStream\TwitchApi\Helix\Api\Channels\Response\FollowedChannelsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class ChannelsApi extends AbstractApi
{
    private const string BASE_PATH = 'channels';

    /**
     * Gets information about one or more channels.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/channels
     *
     * @param AccessTokenInterface         $accessToken Requires an app access token or user access token.
     */
    public function getChannelInformation(
        GetChannelInformationRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelInformationResponse {
        return $this->get(
            self::BASE_PATH,
            ChannelInformationResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterIds,
            ],
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
     * @param AccessTokenInterface            $accessToken Requires a user access token that includes the
     *                                                     channel:manage:broadcast scope.
     */
    public function modifyChannelInformation(
        ModifyChannelInformationRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->patchWithoutResponse(
            self::BASE_PATH,
            $accessToken,
            $this->normalizer->normalize($request->information),
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
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
     * @param AccessTokenInterface     $accessToken Requires a user access token that includes the channel:read:editors
     *                                              scope.
     */
    public function getChannelEditors(
        GetChannelEditorsRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelEditorsResponse {
        return $this->get(
            self::BASE_PATH . '/editors',
            ChannelEditorsResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
            ],
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
     * @param AccessTokenInterface       $accessToken Requires a user access token that includes the user:read:follows
     *                                                scope.
     */
    public function getFollowedChannels(
        GetFollowedChannelsRequest $request,
        AccessTokenInterface $accessToken,
    ): FollowedChannelsResponse {
        $query = array_filter(
            [
                'user_id'        => $request->userId,
                'broadcaster_id' => $request->broadcasterId,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/followed',
            FollowedChannelsResponse::class,
            $accessToken,
            $query,
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
     * @param AccessTokenInterface       $accessToken Requires a user access token that includes the
     *                                                moderator:read:followers scope.
     *
     * @see https://dev.twitch.tv/docs/api/guide#pagination Pagination
     */
    public function getChannelFollowers(
        GetChannelFollowersRequest $request,
        AccessTokenInterface $accessToken,
    ): ChannelFollowersResponse {
        $query = array_filter(
            [
                'user_id'        => $request->userId,
                'broadcaster_id' => $request->broadcasterId,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(
            self::BASE_PATH . '/followers',
            ChannelFollowersResponse::class,
            $accessToken,
            $query,
        );
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Users\Request\BlockUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\GetUserActiveExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\GetUserBlockListRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\GetUsersRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\UnblockUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\UpdateUserExtensionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Request\UpdateUserRequest;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UserActiveExtensionsResponse;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UserBlockListResponse;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UserExtensionsResponse;
use SimplyStream\TwitchApi\Helix\Api\Users\Response\UsersResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class UsersApi extends AbstractApi
{
    private const string BASE_PATH = 'users';

    /**
     * Gets information about one or more users.
     *
     * You may look up users using their user ID, login name, or both but the sum total of the number of users you may
     * look up is 100. For example, you may specify 50 IDs and 50 names or 100 IDs or names, but you cannot specify 100
     * IDs and 100 names.
     *
     * If you don’t specify IDs or login names, the request returns information about the user in the access token if
     * you specify a user access token.
     *
     * To include the user’s verified email address in the response, you must use a user access token that includes the
     * user:read:email scope.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/users
     *
     * @param AccessTokenInterface $accessToken Requires an app access token or user access token.
     */
    public function getUsers(
        GetUsersRequest $request,
        AccessTokenInterface $accessToken,
    ): UsersResponse {
        $query = array_filter(
            [
                'id'    => $request->ids,
                'login' => $request->logins,
            ],
            static fn (mixed $v): bool => $v !== [],
        );

        return $this->get(self::BASE_PATH, UsersResponse::class, $accessToken, $query);
    }

    /**
     * Updates the specified user’s information. The user ID in the OAuth token identifies the user whose information
     * you want to update.
     *
     * To include the user’s verified email address in the response, the user access token must also include the
     * user:read:email scope.
     *
     * Authorization
     * Requires a user access token that includes the user:edit scope.
     *
     * URL
     * PUT https://api.twitch.tv/helix/users
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the user:edit scope.
     */
    public function updateUser(
        UpdateUserRequest $request,
        AccessTokenInterface $accessToken,
    ): UsersResponse {
        // description is kept even when an empty string: "?description=" clears the description.
        $query = $request->description !== null ? ['description' => $request->description] : [];

        return $this->put(self::BASE_PATH, UsersResponse::class, $accessToken, query: $query);
    }

    /**
     * Gets the list of users that the broadcaster has blocked. Read More
     *
     * Authorization
     * Requires a user access token that includes the user:read:blocked_users scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/users/blocks
     *
     * @param AccessTokenInterface    $accessToken Requires a user access token that includes the user:read:blocked_users
     *                                             scope.
     */
    public function getUserBlockList(
        GetUserBlockListRequest $request,
        AccessTokenInterface $accessToken,
    ): UserBlockListResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'first'          => $request->first,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/blocks', UserBlockListResponse::class, $accessToken, $query);
    }

    /**
     * Blocks the specified user from interacting with or having contact with the broadcaster. The user ID in the OAuth
     * token identifies the broadcaster who is blocking the user.
     *
     * To learn more about blocking users, see Block Other Users on Twitch.
     *
     * Authorization
     * Requires a user access token that includes the user:manage:blocked_users scope.
     *
     * URL
     * PUT https://api.twitch.tv/helix/users/blocks
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the user:manage:blocked_users
     *                                          scope.
     */
    public function blockUser(
        BlockUserRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $query = array_filter(
            [
                'target_user_id' => $request->targetUserId,
                'source_context' => $request->sourceContext?->value,
                'reason'         => $request->reason?->value,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        $this->putWithoutResponse(self::BASE_PATH . '/blocks', $accessToken, query: $query);
    }

    /**
     * Removes the user from the broadcaster’s list of blocked users. The user ID in the OAuth token identifies the
     * broadcaster who’s removing the block.
     *
     * Authorization
     * Requires a user access token that includes the user:manage:blocked_users scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/users/blocks
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the user:manage:blocked_users
     *                                          scope.
     */
    public function unblockUser(
        UnblockUserRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH . '/blocks',
            $accessToken,
            [
                'target_user_id' => $request->targetUserId,
            ],
        );
    }

    /**
     * Gets a list of all extensions (both active and inactive) that the broadcaster has installed. The user ID in the
     * access token identifies the broadcaster.
     *
     * Authorization
     * Requires a user access token that includes the user:read:broadcast or user:edit:broadcast scope. To include
     * inactive extensions, you must include the user:edit:broadcast scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/users/extensions/list
     *
     * @param AccessTokenInterface $accessToken Requires a user access token that includes the user:read:broadcast or
     *                                          user:edit:broadcast scope. To include inactive extensions, you must
     *                                          include the user:edit:broadcast scope.
     */
    public function getUserExtensions(
        AccessTokenInterface $accessToken,
    ): UserExtensionsResponse {
        return $this->get(self::BASE_PATH . '/extensions/list', UserExtensionsResponse::class, $accessToken);
    }

    /**
     * Gets the active extensions that the broadcaster has installed for each configuration.
     *
     * NOTE: To include extensions that you have under development, you must specify a user access token that includes
     * the user:read:broadcast or user:edit:broadcast scope.
     *
     * Authorization
     * Requires an app access token or user access token.
     *
     * URL
     * GET https://api.twitch.tv/helix/users/extensions
     *
     * @param AccessTokenInterface           $accessToken Requires an app access token or user access token.
     */
    public function getUserActiveExtensions(
        GetUserActiveExtensionsRequest $request,
        AccessTokenInterface $accessToken,
    ): UserActiveExtensionsResponse {
        $query = array_filter(
            [
                'user_id' => $request->userId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::BASE_PATH . '/extensions', UserActiveExtensionsResponse::class, $accessToken, $query);
    }

    /**
     * Updates an installed extension’s information. You can update the extension’s activation state, ID, and version
     * number. The user ID in the access token identifies the broadcaster whose extensions you’re updating.
     *
     * NOTE: If you try to activate an extension under multiple extension types, the last write wins (and there is no
     * guarantee of write order).
     *
     * Authorization
     * Requires a user access token that includes the user:edit:broadcast scope.
     *
     * URL
     * PUT https://api.twitch.tv/helix/users/extensions
     *
     * @param AccessTokenInterface        $accessToken Requires a user access token that includes the
     *                                                 user:edit:broadcast scope.
     */
    public function updateUserExtensions(
        UpdateUserExtensionsRequest $request,
        AccessTokenInterface $accessToken,
    ): UserActiveExtensionsResponse {
        return $this->put(
            self::BASE_PATH . '/extensions',
            UserActiveExtensionsResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->extensions),
        );
    }
}

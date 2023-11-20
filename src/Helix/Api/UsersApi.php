<?php

namespace SimplyStream\TwitchApiBundle\Helix\Api;

use CuyZ\Valinor\Mapper\MappingError;
use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApiBundle\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApiBundle\Helix\Models\TwitchPaginatedDataResponse;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\UpdateUserExtension;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\User;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\UserActiveExtension;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\UserBlock;
use SimplyStream\TwitchApiBundle\Helix\Models\Users\UserExtension;
use Webmozart\Assert\Assert;

class UsersApi extends AbstractApi
{
    const BASE_PATH = 'users';

    /**
     * Gets information about one or more users.
     *
     * You may look up users using their user ID, login name, or both but the sum total of the number of users you may look up is 100. For
     * example, you may specify 50 IDs and 50 names or 100 IDs or names, but you cannot specify 100 IDs and 100 names.
     *
     * If you don’t specify IDs or login names, the request returns information about the user in the access token if you specify a user
     * access token.
     *
     * To include the user’s verified email address in the response, you must use a user access token that includes the user:read:email
     * scope.
     *
     * Authentication:
     * Requires an app access token or user access token.
     *
     * @param array                     $ids    The ID of the user to get. To specify more than one user, include the id parameter for each
     *                                          user to get. For example, id=1234&id=5678. The maximum number of IDs you may specify is
     *                                          100.
     * @param array                     $logins The login name of the user to get. To specify more than one user, include the login
     *                                          parameter for each user to get. For example, login=foo&login=bar. The maximum number of
     *                                          login names you may specify is 100.
     * @param AccessTokenInterface|null $accessToken
     *
     * @return TwitchDataResponse<User[]>
     * @throws JsonException
     */
    public function getUsers(
        array $ids = [],
        array $logins = [],
        AccessTokenInterface $accessToken = null
    ): TwitchDataResponse {
        Assert::greaterThan(count($ids) + count($logins), 0, 'You need to specify at least one "id" or "login"');
        Assert::lessThanEq(count($ids) + count($logins), 100, 'You can only request a total amount of 100 users at once');

        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'id' => $ids,
                'login' => $logins,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, User::class),
            accessToken: $accessToken
        );
    }

    /**
     * Updates the specified user’s information. The user ID in the OAuth token identifies the user whose information you want to update.
     *
     * To include the user’s verified email address in the response, the user access token must also include the user:read:email scope.
     *
     * Authentication:
     * Requires a user access token that includes the user:edit scope.
     *
     * @param AccessTokenInterface $accessToken
     * @param string|null          $description The string to update the channel’s description to. The description is limited to a maximum
     *                                          of 300 characters.
     *
     *                                          To remove the description, specify this parameter but don’t set it’s value (for example,
     *                                          ?description=).
     *
     * @return TwitchDataResponse<User[]>
     * @throws JsonException
     */
    public function updateUser(
        AccessTokenInterface $accessToken,
        string $description = null
    ): TwitchDataResponse {
        Assert::maxLength($description, 300, "A description can not be longer than 300 characters");

        return $this->sendRequest(
            path: self::BASE_PATH,
            query: [
                'description' => $description,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, User::class),
            method: 'PUT',
            accessToken: $accessToken
        );
    }

    /**
     * Gets the list of users that the broadcaster has blocked. Read More
     *
     * Authentication:
     * Requires a user access token that includes the user:read:blocked_users scope.
     *
     * @param string               $broadcasterId The ID of the broadcaster whose list of blocked users you want to get.
     * @param AccessTokenInterface $accessToken
     * @param int                  $first         The maximum number of items to return per page in the response. The minimum page size is
     *                                            1 item per page and the maximum is 100. The default is 20.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination object in the response
     *                                            contains the cursor’s value.
     *
     * @return TwitchDataResponse<UserBlock[]>
     * @throws JsonException
     * @throws MappingError
     */
    public function getUserBlockList(
        string $broadcasterId,
        AccessTokenInterface $accessToken,
        int $first = 20,
        string $after = null
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/blocks',
            query: [
                'broadcaster_id' => $broadcasterId,
                'first' => $first,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, UserBlock::class),
            accessToken: $accessToken
        );
    }

    /**
     * Blocks the specified user from interacting with or having contact with the broadcaster. The user ID in the OAuth token identifies
     * the broadcaster who is blocking the user.
     *
     * To learn more about blocking users, see Block Other Users on Twitch.
     *
     * Authentication:
     * Requires a user access token that includes the user:manage:blocked_users scope.
     *
     * @param string               $targetUserId  The ID of the user to block. The API ignores the request if the broadcaster has already
     *                                            blocked the user.
     * @param AccessTokenInterface $accessToken
     * @param string|null          $sourceContext The location where the harassment took place that is causing the brodcaster to block the
     *                                            user. Possible values are:
     *                                            - chat
     *                                            - whisper
     * @param string|null          $reason        The reason that the broadcaster is blocking the user. Possible values are:
     *                                            - harassment
     *                                            - spam
     *                                            - other
     *
     * @return void
     * @throws JsonException
     */
    public function blockUser(
        string $targetUserId,
        AccessTokenInterface $accessToken,
        string $sourceContext = null,
        string $reason = null
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/blocks',
            query: [
                'target_user_id' => $targetUserId,
                'source_context' => $sourceContext,
                'reason' => $reason,
            ],
            method: 'PUT',
            accessToken: $accessToken
        );
    }

    /**
     * Removes the user from the broadcaster’s list of blocked users. The user ID in the OAuth token identifies the broadcaster who’s
     * removing the block.
     *
     * Authentication:
     * Requires a user access token that includes the user:manage:blocked_users scope.
     *
     * @param string               $targetUserId The ID of the user to remove from the broadcaster’s list of blocked users. The API ignores
     *                                           the request if the broadcaster hasn’t blocked the user.
     * @param AccessTokenInterface $accessToken
     *
     * @return void
     * @throws JsonException
     */
    public function unblockUser(
        string $targetUserId,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/blocks',
            query: [
                'target_user_id' => $targetUserId,
            ],
            method: 'DELETE',
            accessToken: $accessToken
        );
    }

    /**
     * Gets a list of all extensions (both active and inactive) that the broadcaster has installed. The user ID in the access token
     * identifies the broadcaster.
     *
     * Authentication:
     * Requires a user access token that includes the user:read:broadcast or user:edit:broadcast scope. To include inactive extensions, you
     * must include the user:edit:broadcast scope.
     *
     * @param AccessTokenInterface $accessToken
     *
     * @return TwitchDataResponse<UserExtension[]>
     * @throws JsonException
     */
    public function getUserExtensions(
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/extensions/list',
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, UserExtension::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets the active extensions that the broadcaster has installed for each configuration.
     *
     * NOTE: To include extensions that you have under development, you must specify a user access token that includes the
     * user:read:broadcast or user:edit:broadcast scope.
     *
     * Authentication:
     * Requires an app access token or user access token.
     *
     * @param string|null               $userId The ID of the broadcaster whose active extensions you want to get.
     *
     *                                               This parameter is required if you specify an app access token and is optional if you
     *                                               specify a user access token. If you specify a user access token and don’t specify this
     *                                               parameter, the API uses the user ID from the access token.
     * @param AccessTokenInterface|null $accessToken
     *
     * @return TwitchDataResponse<UserActiveExtension[]>
     * @throws JsonException
     */
    public function getUserActiveExtensions(
        string $userId = null,
        AccessTokenInterface $accessToken = null
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/extensions',
            query: [
                'user_id' => $userId,
            ],
            type: sprintf('%s<%s>', TwitchDataResponse::class, UserActiveExtension::class),
            accessToken: $accessToken
        );
    }

    /**
     * Updates an installed extension’s information. You can update the extension’s activation state, ID, and version number. The user ID
     * in the access token identifies the broadcaster whose extensions you’re updating.
     *
     * NOTE: If you try to activate an extension under multiple extension types, the last write wins (and there is no guarantee of write
     * order).
     *
     * Authentication:
     * Requires a user access token that includes the user:edit:broadcast scope.
     *
     * @param UpdateUserExtension  $body
     * @param AccessTokenInterface $accessToken
     *
     * @return TwitchDataResponse<UserActiveExtension>
     * @throws JsonException
     */
    public function updateUserExtensions(
        UpdateUserExtension $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/extensions',
            type: sprintf('%s<%s>', TwitchDataResponse::class, UserActiveExtension::class),
            method: 'PUT',
            body: $body,
            accessToken: $accessToken
        );
    }
}
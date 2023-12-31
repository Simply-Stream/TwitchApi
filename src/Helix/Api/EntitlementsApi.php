<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlement;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\DropEntitlementUpdate;
use SimplyStream\TwitchApi\Helix\Models\Entitlements\UpdateDropEntitlementRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;

class EntitlementsApi extends AbstractApi
{
    protected const BASE_PATH = 'entitlements';

    /**
     * Gets an organization’s list of entitlements that have been granted to a game, a user, or both.
     *
     * The following table identifies the request parameters that you may specify based on the type of access token
     * used.
     *
     * Access token type | Parameter        | Description
     * ------------------|------------------|--------------------------------------------------------------------------
     * App               | None             | If you don’t specify request parameters, the request returns all
     *                   |                  | entitlements that your organization owns.
     * ------------------|------------------|--------------------------------------------------------------------------
     * App               | user_id          | The request returns all entitlements for any game that the organization
     *                   |                  | granted to the specified user.
     * ------------------|------------------|--------------------------------------------------------------------------
     * App               | user_id, game_id | The request returns all entitlements that the specified game granted to
     *                   |                  | the specified user.
     * ------------------|------------------|--------------------------------------------------------------------------
     * App               | game_id          | The request returns all entitlements that the specified game granted to
     *                   |                  | all entitled users.
     * ------------------|------------------|--------------------------------------------------------------------------
     * User              | None             | If you don’t specify request parameters, the request returns all
     *                   |                  | entitlements for any game that organization granted to the user
     *                   |                  | identified in the access token.
     * ------------------|------------------|--------------------------------------------------------------------------
     * User              | user_id          | Invalid.
     * ------------------|------------------|--------------------------------------------------------------------------
     * User              | user_id, game_id | Invalid.
     * ------------------|------------------|--------------------------------------------------------------------------
     * User              | game_id          | The request returns all entitlements that the specified game granted to
     *                   |                  | the user identified in the token.
     * ------------------|------------------|--------------------------------------------------------------------------
     *
     * Authorization
     * Requires an app access token or user access token. The client ID in the access token must own the game.
     *
     * URL
     * GET https://api.twitch.tv/helix/entitlements/drops
     *
     * @param AccessTokenInterface $accessToken            Requires an app access token or user access token. The
     *                                                     client ID in the access token must own the game.
     * @param string|null          $id                     An ID that identifies the entitlement to get. Include this
     *                                                     parameter for each entitlement you want to get. For example,
     *                                                     id=1234&id=5678. You may specify a maximum of 100 IDs.
     * @param string|null          $userId                 An ID that identifies a user that was granted entitlements.
     * @param string|null          $gameId                 An ID that identifies a game that offered entitlements.
     * @param string|null          $fulfillmentStatus      The entitlement’s fulfillment status. Used to filter the
     *                                                     list to only those with the specified status. Possible
     *                                                     values are:
     *                                                     - CLAIMED
     *                                                     - FULFILLED
     * @param string|null          $after                  The cursor used to get the next page of results. The
     *                                                     Pagination object in the response contains the cursor’s
     *                                                     value.
     * @param int                  $first                  The maximum number of entitlements to return per page in the
     *                                                     response. The minimum page size is 1 entitlement per page
     *                                                     and the maximum is 1000. The default is 20.
     *
     * @return TwitchPaginatedDataResponse<DropEntitlement[]>
     */
    public function getDropsEntitlements(
        AccessTokenInterface $accessToken,
        string $id = null,
        string $userId = null,
        string $gameId = null,
        string $fulfillmentStatus = null,
        string $after = null,
        int $first = 20,
    ): TwitchPaginatedDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/drops',
            query: [
                'id' => $id,
                'user_id' => $userId,
                'game_id' => $gameId,
                'fulfillment_status' => $fulfillmentStatus,
                'after' => $after,
                'first' => $first,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, DropEntitlement::class),
            accessToken: $accessToken
        );
    }

    /**
     * Updates the Drop entitlement’s fulfillment status.
     *
     * The following table identifies which entitlements are updated based on the type of access token used.
     *
     * Access token type | Data that’s updated
     * ------------------|-----------------------------------------------------------------------------------------------------------------
     * App               | Updates all entitlements with benefits owned by the organization in the access token.
     * ------------------|-----------------------------------------------------------------------------------------------------------------
     * User              | Updates all entitlements owned by the user in the access token and where the benefits are
     * owned by the
     *                   | organization in the access token.
     * ------------------------------------------------------------------------------------------------------------------------------------
     *
     * Authorization
     * Requires an app access token or user access token. The client ID in the access token must own the game.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/entitlements/drops
     *
     * @param UpdateDropEntitlementRequest $body
     * @param AccessTokenInterface         $accessToken Requires an app access token or user access token. The client
     *                                                  ID in the access token must own the game.
     *
     * @return TwitchDataResponse<DropEntitlementUpdate[]>
     */
    public function updateDropsEntitlements(
        UpdateDropEntitlementRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/drops',
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, DropEntitlementUpdate::class),
            method: 'PATCH',
            body: $body,
            accessToken: $accessToken
        );
    }
}

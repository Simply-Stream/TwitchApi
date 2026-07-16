<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Entitlements\Request\GetDropsEntitlementsRequest;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Request\UpdateDropsEntitlementsRequest;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Response\DropsEntitlementsResponse;
use SimplyStream\TwitchApi\Helix\Api\Entitlements\Response\UpdateDropsEntitlementsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class EntitlementsApi extends AbstractApi
{
    private const string BASE_PATH = 'entitlements';

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
     * @param AccessTokenInterface        $accessToken Requires an app access token or user access token. The client ID
     *                                                 in the access token must own the game.
     */
    public function getDropsEntitlements(
        GetDropsEntitlementsRequest $request,
        AccessTokenInterface $accessToken,
    ): DropsEntitlementsResponse {
        $query = array_filter(
            [
                'id'                 => $request->ids,
                'user_id'            => $request->userId,
                'game_id'            => $request->gameId,
                'fulfillment_status' => $request->fulfillmentStatus?->value,
                'after'              => $request->after,
                'first'              => $request->first,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(
            self::BASE_PATH . '/drops',
            DropsEntitlementsResponse::class,
            $accessToken,
            $query,
        );
    }

    /**
     * Updates the Drop entitlement’s fulfillment status.
     *
     * The following table identifies which entitlements are updated based on the type of access token used.
     *
     * Access token type | Data that’s updated
     * ------------------|-----------------------------------------------------------------------------------------------
     * App               | Updates all entitlements with benefits owned by the organization in the access token.
     * ------------------|-----------------------------------------------------------------------------------------------
     * User              | Updates all entitlements owned by the user in the access token and where the benefits are
     *                   | owned by the organization in the access token.
     * ------------------------------------------------------------------------------------------------------------------
     *
     * Authorization
     * Requires an app access token or user access token. The client ID in the access token must own the game.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/entitlements/drops
     *
     * @param AccessTokenInterface           $accessToken Requires an app access token or user access token. The client
     *                                                    ID in the access token must own the game.
     */
    public function updateDropsEntitlements(
        UpdateDropsEntitlementsRequest $request,
        AccessTokenInterface $accessToken,
    ): UpdateDropsEntitlementsResponse {
        return $this->patch(
            self::BASE_PATH . '/drops',
            UpdateDropsEntitlementsResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->entitlement),
        );
    }
}

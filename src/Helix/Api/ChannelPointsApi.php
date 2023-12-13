<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CreateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomReward;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\CustomRewardRedemption;
use SimplyStream\TwitchApi\Helix\Models\ChannelPoints\RedemptionStatusRequest;
use SimplyStream\TwitchApi\Helix\Models\TwitchDataResponse;
use SimplyStream\TwitchApi\Helix\Models\TwitchPaginatedDataResponse;
use Webmozart\Assert\Assert;

class ChannelPointsApi extends AbstractApi
{
    protected const BASE_PATH = 'channel_points';

    /**
     * Creates a Custom Reward in the broadcaster’s channel. The maximum number of custom rewards per channel is 50,
     * which includes both enabled and disabled rewards.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:redemptions scope.
     *
     * URL
     * POST https://api.twitch.tv/helix/channel_points/custom_rewards
     *
     * @param string                    $broadcasterId The ID of the broadcaster to add the custom reward to. This ID
     *                                                 must match the user ID found in the OAuth token.
     * @param CreateCustomRewardRequest $body
     * @param AccessTokenInterface      $accessToken   Requires a user access token that includes the
     *                                                 channel:manage:redemptions scope.
     *
     * @return TwitchDataResponse<CustomReward[]>
     */
    public function createCustomRewards(
        string $broadcasterId,
        CreateCustomRewardRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/custom_rewards',
            query: [
                'broadcaster_id' => $broadcasterId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, CustomReward::class),
            body: $body,
            accessToken: $accessToken,
        );
    }

    /**
     * Deletes a custom reward that the broadcaster created.
     *
     * The app used to create the reward is the only app that may delete it. If the reward’s redemption status is
     * UNFULFILLED at the time the reward is deleted, its redemption status is marked as FULFILLED.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:redemptions scope.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/channel_points/custom_rewards
     *
     * @param string               $broadcasterId The ID of the broadcaster that created the custom reward. This ID
     *                                            must match the user ID found in the OAuth token.
     * @param string               $id            The ID of the custom reward to delete.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the
     *                                            channel:manage:redemptions scope.
     */
    public function deleteCustomRewards(
        string $broadcasterId,
        string $id,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/custom_rewards',
            query: [
                'broadcaster_id' => $broadcasterId,
                'id' => $id,
            ],
            method: 'DELETE',
            accessToken: $accessToken
        );
    }

    /**
     * Gets a list of custom rewards that the specified broadcaster created.
     *
     * NOTE: A channel may offer a maximum of 50 rewards, which includes both enabled and disabled rewards.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:redemptions or channel:manage:redemptions scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/channel_points/custom_rewards
     *
     * @param string               $broadcasterId         The ID of the broadcaster whose custom rewards you want to
     *                                                    get. This ID must match the user ID found in the OAuth token.
     * @param AccessTokenInterface $accessToken           Requires a user access token that includes the
     *                                                    channel:read:redemptions or channel:manage:redemptions scope.
     * @param string|null          $id                    A list of IDs to filter the rewards by. To specify more than
     *                                                    one ID, include this parameter for each reward you want to
     *                                                    get. For example, id=1234&id=5678. You may specify a maximum
     *                                                    of 50 IDs. Duplicate IDs are ignored. The response contains
     *                                                    only the IDs that were found. If none of the IDs were found,
     *                                                    the response is 404 Not Found.
     * @param bool                 $onlyManageableRewards A Boolean value that determines whether the response contains
     *                                                    only the custom rewards that the app may manage (the app is
     *                                                    identified by the ID in the Client-Id header). Set to true to
     *                                                    get only the custom rewards that the app may manage. The
     *                                                    default is false.
     *
     * @return TwitchDataResponse<CustomReward[]>
     */
    public function getCustomReward(
        string $broadcasterId,
        AccessTokenInterface $accessToken,
        string $id = null,
        bool $onlyManageableRewards = false
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/custom_rewards',
            query: [
                'broadcaster_id' => $broadcasterId,
                'id' => $id,
                'only_manageable_rewards' => $onlyManageableRewards,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, CustomReward::class),
            accessToken: $accessToken
        );
    }

    /**
     * Gets a list of redemptions for the specified custom reward. The app used to create the reward is the only app
     * that may get the redemptions.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:redemptions or channel:manage:redemptions scope.
     *
     * URL
     * GET https://api.twitch.tv/helix/channel_points/custom_rewards/redemptions
     *
     * @param string               $broadcasterId The ID of the broadcaster that owns the custom reward. This ID must
     *                                            match the user ID found in the user OAuth token.
     * @param string               $rewardId      The ID that identifies the custom reward whose redemptions you want
     *                                            to get.
     * @param AccessTokenInterface $accessToken   Requires a user access token that includes the
     *                                            channel:read:redemptions or channel:manage:redemptions scope.
     * @param string|null          $status        The status of the redemptions to return. The possible case-sensitive
     *                                            values are:
     *                                            - CANCELED
     *                                            - FULFILLED
     *                                            - UNFULFILLED
     *                                            NOTE: This field is required only if you don’t specify the id query
     *                                            parameter.
     *
     *                                            NOTE: Canceled and fulfilled redemptions are returned for only a few
     *                                            days after they’re canceled or fulfilled.
     * @param string|null          $id            A list of IDs to filter the redemptions by. To specify more than one
     *                                            ID, include this parameter for each redemption you want to get. For
     *                                            example, id=1234&id=5678. You may specify a maximum of 50 IDs.
     *
     *                                            Duplicate IDs are ignored. The response contains only the IDs that
     *                                            were found. If none of the IDs were found, the response is 404 Not
     *                                            Found.
     * @param string               $sort          The order to sort redemptions by. The possible case-sensitive values
     *                                            are:
     *                                            - OLDEST
     *                                            - NEWEST
     *                                            The default is OLDEST.
     * @param string|null          $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     * @param int                  $first         The maximum number of redemptions to return per page in the response.
     *                                            The minimum page size is 1 redemption per page and the maximum is 50.
     *                                            The default is 20.
     *
     * @return TwitchPaginatedDataResponse<CustomRewardRedemption[]>
     */
    public function getCustomRewardRedemption(
        string $broadcasterId,
        string $rewardId,
        AccessTokenInterface $accessToken,
        ?string $status = null,
        ?string $id = null,
        string $sort = 'OLDEST',
        ?string $after = null,
        int $first = 20,
    ): TwitchDataResponse {
        if (!$id) {
            Assert::stringNotEmpty($status);
        }

        return $this->sendRequest(
            path: self::BASE_PATH . '/custom_rewards/redemptions',
            query: [
                'broadcaster_id' => $broadcasterId,
                'reward_id' => $rewardId,
                'status' => $status,
                'id' => $id,
                'sort' => $sort,
                'after' => $after,
                'first' => $first,
            ],
            type: sprintf('%s<%s[]>', TwitchPaginatedDataResponse::class, CustomRewardRedemption::class),
            accessToken: $accessToken
        );
    }

    /**
     * Updates a custom reward. The app used to create the reward is the only app that may update the reward.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:redemptions scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/channel_points/custom_rewards
     *
     * @param string                    $broadcasterId The ID of the broadcaster that’s updating the reward. This ID
     *                                                 must match the user ID found in the OAuth token.
     * @param string                    $id            The ID of the reward to update.
     * @param CreateCustomRewardRequest $body
     * @param AccessTokenInterface      $accessToken   Requires a user access token that includes the
     *                                                 channel:manage:redemptions scope.
     *
     * @return TwitchDataResponse<CustomReward[]>
     */
    public function updateCustomReward(
        string $broadcasterId,
        string $id,
        CreateCustomRewardRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/custom_rewards',
            query: [
                'broadcaster_id' => $broadcasterId,
                'id' => $id,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, CustomReward::class),
            method: 'PATCH',
            body: $body,
            accessToken: $accessToken
        );
    }

    /**
     * Updates a redemption’s status. You may update a redemption only if its status is UNFULFILLED. The app used to
     * create the reward is the only app that may update the redemption.
     *
     * Authorization
     * Requires a user access token that includes the channel:manage:redemptions scope.
     *
     * URL
     * PATCH https://api.twitch.tv/helix/channel_points/custom_rewards/redemptions
     *
     * @param string                  $broadcasterId A list of IDs that identify the redemptions to update. To specify
     *                                               more than one ID, include this parameter for each redemption you
     *                                               want to update. For example, id=1234&id=5678. You may specify a
     *                                               maximum of 50 IDs.
     * @param string                  $id            The ID of the broadcaster that’s updating the redemption. This ID
     *                                               must match the user ID associated with the user OAuth token.
     * @param string                  $rewardId      The ID that identifies the reward that’s been redeemed.
     * @param RedemptionStatusRequest $body
     * @param AccessTokenInterface    $accessToken   Requires a user access token that includes the
     *                                               channel:manage:redemptions scope.
     *
     * @return TwitchDataResponse<CustomRewardRedemption[]>
     */
    public function updateRedemptionStatus(
        string $broadcasterId,
        string $id,
        string $rewardId,
        RedemptionStatusRequest $body,
        AccessTokenInterface $accessToken
    ): TwitchDataResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/custom_rewards/redemptions',
            query: [
                'broadcaster_id' => $broadcasterId,
                'id' => $id,
                'reward_id' => $rewardId,
            ],
            type: sprintf('%s<%s[]>', TwitchDataResponse::class, CustomRewardRedemption::class),
            body: $body,
            accessToken: $accessToken
        );
    }
}

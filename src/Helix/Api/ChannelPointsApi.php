<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\CreateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\DeleteCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\GetCustomRewardRedemptionRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\GetCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\UpdateCustomRewardRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request\UpdateRedemptionStatusRequest;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response\CustomRewardRedemptionResponse;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Response\CustomRewardResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class ChannelPointsApi extends AbstractApi
{
    private const string BASE_PATH = 'channel_points';

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
     * @param CreateCustomRewardRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               channel:manage:redemptions scope.
     *
     * @return CustomRewardResponse
     */
    public function createCustomReward(
        CreateCustomRewardRequest $request,
        AccessTokenInterface $accessToken,
    ): CustomRewardResponse {
        return $this->post(
            self::BASE_PATH . '/custom_rewards',
            CustomRewardResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->reward),
            ['broadcaster_id' => $request->broadcasterId],
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
     * @param DeleteCustomRewardRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               channel:manage:redemptions scope.
     */
    public function deleteCustomReward(
        DeleteCustomRewardRequest $request,
        AccessTokenInterface $accessToken,
    ): void {
        $this->delete(
            self::BASE_PATH . '/custom_rewards',
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->id,
            ],
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
     * @param GetCustomRewardRequest $request
     * @param AccessTokenInterface   $accessToken Requires a user access token that includes the channel:read:redemptions
     *                                            or channel:manage:redemptions scope.
     *
     * @return CustomRewardResponse
     */
    public function getCustomReward(
        GetCustomRewardRequest $request,
        AccessTokenInterface $accessToken,
    ): CustomRewardResponse {
        $query = array_filter(
            [
                'broadcaster_id'          => $request->broadcasterId,
                'id'                      => $request->ids,
                'only_manageable_rewards' => $request->onlyManageableRewards,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(
            self::BASE_PATH . '/custom_rewards',
            CustomRewardResponse::class,
            $accessToken,
            $query,
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
     * @param GetCustomRewardRedemptionRequest $request
     * @param AccessTokenInterface             $accessToken Requires a user access token that includes the
     *                                                      channel:read:redemptions or channel:manage:redemptions scope.
     *
     * @return CustomRewardRedemptionResponse
     */
    public function getCustomRewardRedemption(
        GetCustomRewardRedemptionRequest $request,
        AccessTokenInterface $accessToken,
    ): CustomRewardRedemptionResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'reward_id'      => $request->rewardId,
                'status'         => $request->status?->value,
                'id'             => $request->ids,
                'sort'           => $request->sort->value,
                'after'          => $request->after,
                'first'          => $request->first,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(
            self::BASE_PATH . '/custom_rewards/redemptions',
            CustomRewardRedemptionResponse::class,
            $accessToken,
            $query,
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
     * @param UpdateCustomRewardRequest $request
     * @param AccessTokenInterface      $accessToken Requires a user access token that includes the
     *                                               channel:manage:redemptions scope.
     *
     * @return CustomRewardResponse
     */
    public function updateCustomReward(
        UpdateCustomRewardRequest $request,
        AccessTokenInterface $accessToken,
    ): CustomRewardResponse {
        return $this->patch(
            self::BASE_PATH . '/custom_rewards',
            CustomRewardResponse::class,
            $accessToken,
            $this->normalizer->normalize($request->reward),
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->id,
            ],
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
     * @param UpdateRedemptionStatusRequest $request
     * @param AccessTokenInterface          $accessToken Requires a user access token that includes the
     *                                                   channel:manage:redemptions scope.
     *
     * @return CustomRewardRedemptionResponse
     */
    public function updateRedemptionStatus(
        UpdateRedemptionStatusRequest $request,
        AccessTokenInterface $accessToken,
    ): CustomRewardRedemptionResponse {
        return $this->patch(
            self::BASE_PATH . '/custom_rewards/redemptions',
            CustomRewardRedemptionResponse::class,
            $accessToken,
            ['status' => $request->status->value],
            [
                'broadcaster_id' => $request->broadcasterId,
                'id'             => $request->ids,
                'reward_id'      => $request->rewardId,
            ],
        );
    }
}

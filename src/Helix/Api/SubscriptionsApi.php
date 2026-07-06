<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request\CheckUserSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request\GetBroadcasterSubscriptionsRequest;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response\BroadcasterSubscriptionsResponse;
use SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response\CheckUserSubscriptionResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

final class SubscriptionsApi extends AbstractApi
{
    private const string BASE_PATH = 'subscriptions';

    /**
     * Gets a list of users that subscribe to the specified broadcaster.
     *
     * Authorization
     * Requires a user access token that includes the channel:read:subscriptions scope.
     *
     * A Twitch extensions may use an app access token if the broadcaster has granted the channel:read:subscriptions
     * scope from within the Twitch Extensions manager.
     *
     * URL
     * GET https://api.twitch.tv/helix/subscriptions
     *
     * @param GetBroadcasterSubscriptionsRequest $request
     * @param AccessTokenInterface               $accessToken Requires a user access token that includes the
     *                                                        channel:read:subscriptions scope.
     *
     * @return BroadcasterSubscriptionsResponse
     */
    public function getBroadcasterSubscriptions(
        GetBroadcasterSubscriptionsRequest $request,
        AccessTokenInterface $accessToken,
    ): BroadcasterSubscriptionsResponse {
        $query = array_filter(
            [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userIds,
                'first'          => $request->first,
                'before'         => $request->before,
                'after'          => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null && $v !== [],
        );

        return $this->get(self::BASE_PATH, BroadcasterSubscriptionsResponse::class, $accessToken, $query);
    }

    /**
     * Checks whether the user subscribes to the broadcaster’s channel.
     *
     * Authorization
     * Requires a user access token that includes the user:read:subscriptions scope.
     *
     * A Twitch extensions may use an app access token if the broadcaster has granted the user:read:subscriptions scope
     * from within the Twitch Extensions manager.
     *
     * URL
     * GET https://api.twitch.tv/helix/subscriptions/user
     *
     * @param CheckUserSubscriptionRequest $request
     * @param AccessTokenInterface         $accessToken Requires a user access token that includes the
     *                                                  user:read:subscriptions scope.
     *
     * @return CheckUserSubscriptionResponse
     */
    public function checkUserSubscription(
        CheckUserSubscriptionRequest $request,
        AccessTokenInterface $accessToken,
    ): CheckUserSubscriptionResponse {
        return $this->get(
            self::BASE_PATH . '/user',
            CheckUserSubscriptionResponse::class,
            $accessToken,
            [
                'broadcaster_id' => $request->broadcasterId,
                'user_id'        => $request->userId,
            ],
        );
    }
}

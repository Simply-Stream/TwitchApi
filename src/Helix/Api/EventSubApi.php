<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\CreateEventSubSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Request\GetEventSubSubscriptionsRequest;
use SimplyStream\TwitchApi\Helix\Api\EventSub\Response\EventSubSubscriptionsResponse;
use SimplyStream\TwitchApi\Helix\Authentication\AccessTokenInterface;

class EventSubApi extends AbstractApi
{
    protected const string PATH = 'eventsub/subscriptions';

    /**
     * Creates an EventSub subscription.
     *
     * Authorization
     * If you use webhooks to receive events, the request must specify an app access token. The request will fail if
     * you use a user access token. If the subscription type requires user authorization, the user must have granted
     * your app (client ID) permissions to receive those events before you subscribe to them. For example, to subscribe
     * to channel.subscribe events, your app must get a user access token that includes the channel:read:subscriptions
     * scope, which adds the required permission to your app access token’s client ID.
     *
     * If you use WebSockets to receive events, the request must specify a user access token. The request will fail if
     * you use an app access token. If the subscription type requires user authorization, the token must include the
     * required scope. However, if the subscription type doesn’t include user authorization, the token may include any
     * scopes or no scopes.
     *
     * URL
     * POST https://api.twitch.tv/helix/eventsub/subscriptions
     *
     * @param AccessTokenInterface              $token   If you use webhooks to receive events, the request must specify an app
     *                                                   access token. The request will fail if you use a user access token. If
     *                                                   the subscription type requires user authorization, the user must have
     *                                                   granted your app (client ID) permissions to receive those events before
     *                                                   you subscribe to them. For example, to subscribe to channel.subscribe
     *                                                   events, your app must get a user access token that includes the
     *                                                   channel:read:subscriptions scope, which adds the required permission to
     *                                                   your app access token’s client ID.
     */
    public function createEventSubSubscription(
        CreateEventSubSubscriptionRequest $request,
        AccessTokenInterface $token,
    ): EventSubSubscriptionsResponse {
        $transport = array_filter(
            [
                'method'     => $request->transport->method,
                'secret'     => $request->transport->secret,
                'callback'   => $request->transport->callback,
                'session_id' => $request->transport->sessionId,
                'conduit_id' => $request->transport->conduitId,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->post(
            self::PATH,
            EventSubSubscriptionsResponse::class,
            $token,
            [
                'type'      => $request->type,
                'version'   => $request->version,
                'condition' => $request->condition,
                'transport' => $transport,
            ],
        );
    }

    /**
     * Deletes an EventSub subscription.
     *
     * Authorization
     * If you use webhooks to receive events, the request must specify an app access token. The request will fail if
     * you use a user access token.
     *
     * If you use WebSockets to receive events, the request must specify a user access token. The request will fail if
     * you use an app access token. The token may include any scopes.
     *
     * URL
     * DELETE https://api.twitch.tv/helix/eventsub/subscriptions
     *
     * @param AccessTokenInterface $token If you use webhooks to receive events, the request must specify an
     *                                    app access token. The request will fail if you use a user access token.
     */
    public function deleteEventSubSubscription(
        string $id,
        AccessTokenInterface $token,
    ): void {
        $this->delete(self::PATH, $token, ['id' => $id]);
    }

    /**
     * Gets a list of EventSub subscriptions that the client in the access token created.
     *
     * Authorization
     * If you use webhooks to receive events, the request must specify an app access token. The request will fail if
     * you use a user access token.
     *
     * If you use WebSockets to receive events, the request must specify a user access token. The request will fail if
     * you use an app access token. The token may include any scopes.
     *
     * URL
     * GET https://api.twitch.tv/helix/eventsub/subscriptions
     *
     * @param AccessTokenInterface            $token   If you use webhooks to receive events, the request must specify an
     *                                                 app access token. The request will fail if you use a user access
     *                                                 token.
     */
    public function getEventSubSubscriptions(
        GetEventSubSubscriptionsRequest $request,
        AccessTokenInterface $token,
    ): EventSubSubscriptionsResponse {
        $query = array_filter(
            [
                'status'  => $request->status?->value,
                'type'    => $request->type,
                'user_id' => $request->userId,
                'after'   => $request->after,
            ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this->get(self::PATH, EventSubSubscriptionsResponse::class, $token, $query);
    }
}

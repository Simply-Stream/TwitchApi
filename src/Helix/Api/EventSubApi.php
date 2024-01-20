<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

use JsonException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use SimplyStream\TwitchApi\Helix\Models\EventSub\CreateEventSubSubscriptionRequest;
use SimplyStream\TwitchApi\Helix\Models\EventSub\EventSubResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\PaginatedEventSubResponse;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Subscription;

class EventSubApi extends AbstractApi
{
    protected const BASE_PATH = 'eventsub';

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
     * @template T of Subscription
     *
     * @param T                    $subscription
     * @param AccessTokenInterface $accessToken If you use webhooks to receive events, the request must specify an app
     *                                          access token. The request will fail if you use a user access token. If
     *                                          the subscription type requires user authorization, the user must have
     *                                          granted your app (client ID) permissions to receive those events before
     *                                          you subscribe to them. For example, to subscribe to channel.subscribe
     *                                          events, your app must get a user access token that includes the
     *                                          channel:read:subscriptions scope, which adds the required permission to
     *                                          your app access token’s client ID.
     *
     * @return EventSubResponse<T[]>
     */
    public function createEventSubSubscription(
        Subscription $subscription,
        AccessTokenInterface $accessToken
    ): EventSubResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/subscriptions',
            type: sprintf('%s[]', Subscription::class),
            method: 'POST',
            // I don't really like this way, but better than nothing at the moment
            body: new CreateEventSubSubscriptionRequest(
                $subscription->getType(),
                $subscription->getVersion(),
                $subscription->getCondition(),
                $subscription->getTransport()
            ),
            accessToken: $accessToken
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
     * @param string               $id
     * @param AccessTokenInterface $accessToken      If you use webhooks to receive events, the request must specify an
     *                                               app access token. The request will fail if you use a user access
     *                                               token.
     *
     * @return void
     */
    public function deleteEventSubSubscription(
        string $id,
        AccessTokenInterface $accessToken
    ): void {
        $this->sendRequest(
            path: self::BASE_PATH . '/subscriptions',
            query: [
                'id' => $id,
            ],
            method: 'DELETE',
            accessToken: $accessToken
        );
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
     * @param string|null          $status           Filter subscriptions by its status. Possible values are:
     *                                               - enabled — The subscription is enabled.
     *                                               - webhook_callback_verification_pending — The subscription is
     *                                               pending verification of the specified callback URL.
     *                                               - webhook_callback_verification_failed — The specified callback
     *                                               URL
     *                                               failed verification.
     *                                               - notification_failures_exceeded — The notification delivery
     *                                               failure rate was too high.
     *                                               - authorization_revoked — The authorization was revoked for one or
     *                                               more users specified in the Condition object.
     *                                               - user_removed — One of the users specified in the Condition
     *                                               object was removed.
     *                                               - version_removed — The subscribed to subscription type and
     *                                               version is no longer supported.
     * @param string|null          $type             Filter subscriptions by subscription type. For a list of
     *                                               subscription types, see Subscription Types.
     * @param string|null          $userId           Filter subscriptions by user ID. The response contains
     *                                               subscriptions where this ID matches a user ID that you specified
     *                                               in the Condition object when you created the subscription.
     * @param string|null          $after            The cursor used to get the next page of results. The pagination
     *                                               object in the response contains the cursor’s value.
     * @param AccessTokenInterface $accessToken      If you use webhooks to receive events, the request must specify an
     *                                               app access token. The request will fail if you use a user access
     *                                               token.
     *
     * @return PaginatedEventSubResponse<Subscription[]>
     * @throws JsonException
     */
    public function getEventSubSubscriptions(
        AccessTokenInterface $accessToken,
        string $status = null,
        string $type = null,
        string $userId = null,
        string $after = null,
    ): PaginatedEventSubResponse {
        return $this->sendRequest(
            path: self::BASE_PATH . '/subscriptions',
            query: [
                'status' => $status,
                'type' => $type,
                'user_id' => $userId,
                'after' => $after,
            ],
            type: sprintf('%s<%s[]>', PaginatedEventSubResponse::class, Subscription::class),
            accessToken: $accessToken
        );
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\EventSub\Request;

use SimplyStream\TwitchApi\Helix\Api\EventSub\SubscriptionStatus;

final readonly class GetEventSubSubscriptionsRequest
{
    /**
     * @param SubscriptionStatus|null $status Filter subscriptions by its status.
     * @param string|null             $type   Filter subscriptions by subscription type. For a list of subscription
     *                                        types, see Subscription Types.
     * @param string|null             $userId Filter subscriptions by user ID. The response contains subscriptions where
     *                                        this ID matches a user ID that you specified in the Condition object when
     *                                        you created the subscription.
     * @param string|null             $after  The cursor used to get the next page of results. The pagination object in
     *                                        the response contains the cursor’s value.
     */
    public function __construct(
        public ?SubscriptionStatus $status = null,
        public ?string $type = null,
        public ?string $userId = null,
        public ?string $after = null,
    ) {
    }
}

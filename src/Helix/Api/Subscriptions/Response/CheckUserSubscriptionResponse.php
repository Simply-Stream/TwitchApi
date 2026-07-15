<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Subscriptions\Response;

use SimplyStream\TwitchApi\Helix\Models\Subscriptions\Subscription;

final readonly class CheckUserSubscriptionResponse
{
    /** @param list<Subscription> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

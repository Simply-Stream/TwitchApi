<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Subscriptions\Request;

use Webmozart\Assert\Assert;

final readonly class GetBroadcasterSubscriptionsRequest
{
    /**
     * @param string       $broadcasterId The broadcaster’s ID. This ID must match the user ID in the access token.
     * @param list<string> $userIds       Filters the list to include only the specified subscribers. To specify more
     *                                    than one subscriber, include this parameter for each subscriber. For example,
     *                                    &user_id=1234&user_id=5678. You may specify a maximum of 100 subscribers.
     * @param int          $first         The maximum number of items to return per page in the response. The minimum
     *                                    page size is 1 item per page and the maximum is 100 items per page. The
     *                                    default is 20.
     * @param string|null  $after         The cursor used to get the next page of results. Do not specify if you set the
     *                                    user_id query parameter. The Pagination object in the response contains the
     *                                    cursor’s value.
     * @param string|null  $before        The cursor used to get the previous page of results. Do not specify if you set
     *                                    the user_id query parameter. The Pagination object in the response contains
     *                                    the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public array $userIds = [],
        public int $first = 20,
        public ?string $after = null,
        public ?string $before = null,
    ) {
        Assert::range($first, 1, 100);
        Assert::maxCount($userIds, 100);
        Assert::allString($userIds);
    }
}

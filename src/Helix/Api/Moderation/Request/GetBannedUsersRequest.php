<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use Webmozart\Assert\Assert;

final readonly class GetBannedUsersRequest
{
    /**
     * @param string       $broadcasterId The ID of the broadcaster whose list of banned users you want to get. This ID
     *                                    must match the user ID in the access token.
     * @param list<string> $userIds       A list of user IDs used to filter the results. To specify more than one ID,
     *                                    include this parameter for each user you want to get. For example,
     *                                    user_id=1234&user_id=5678. You may specify a maximum of 100 IDs.
     *
     *                                    The returned list includes only those users that were banned or put in a
     *                                    timeout. The list is returned in the same order that you specified the IDs.
     * @param int          $first         The maximum number of items to return per page in the response. The minimum
     *                                    page size is 1 item per page and the maximum is 100 items per page. The
     *                                    default is 20.
     * @param string|null  $after         The cursor used to get the next page of results. The Pagination object in the
     *                                    response contains the cursor’s value.
     * @param string|null  $before        The cursor used to get the previous page of results. The Pagination object in
     *                                    the response contains the cursor’s value.
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

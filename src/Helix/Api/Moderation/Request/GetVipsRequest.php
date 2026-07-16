<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use Webmozart\Assert\Assert;

final readonly class GetVipsRequest
{
    /**
     * @param string       $broadcasterId The ID of the broadcaster whose list of VIPs you want to get. This ID must
     *                                    match the user ID in the access token.
     * @param list<string> $userIds       Filters the list for specific VIPs. To specify more than one user, include the
     *                                    user_id parameter for each user to get. For example,
     *                                    &user_id=1234&user_id=5678. The maximum number of IDs that you may specify is
     *                                    100. Ignores the ID of those users in the list that aren’t VIPs.
     * @param int          $first         The maximum number of items to return per page in the response. The minimum
     *                                    page size is 1 item per page and the maximum is 100. The default is 20.
     * @param string|null  $after         The cursor used to get the next page of results. The Pagination object in the
     *                                    response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public array $userIds = [],
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
        Assert::maxCount($userIds, 100);
        Assert::allString($userIds);
    }
}

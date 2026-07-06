<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use Webmozart\Assert\Assert;

final readonly class GetModeratorsRequest
{
    /**
     * @param string       $broadcasterId The ID of the broadcaster whose list of moderators you want to get. This ID
     *                                    must match the user ID in the access token.
     * @param list<string> $userIds       A list of user IDs used to filter the results. To specify more than one ID,
     *                                    include this parameter for each moderator you want to get. For example,
     *                                    user_id=1234&user_id=5678. You may specify a maximum of 100 IDs.
     *
     *                                    The returned list includes only the users from the list who are moderators in
     *                                    the broadcaster’s channel. The list is returned in the same order as you
     *                                    specified the IDs.
     * @param int          $first         The maximum number of items to return per page in the response. The minimum
     *                                    page size is 1 item per page and the maximum is 100 items per page. The
     *                                    default is 20.
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

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Request;

use Webmozart\Assert\Assert;

final readonly class GetFollowedChannelsRequest
{
    /**
     * @param string      $userId        A user’s ID. Returns the list of broadcasters that this user follows. This ID
     *                                   must match the user ID in the user OAuth token.
     * @param string|null $broadcasterId A broadcaster’s ID. Use this parameter to see whether the user follows this
     *                                   broadcaster. If specified, the response contains this broadcaster if the user
     *                                   follows them. If not specified, the response contains all broadcasters that the
     *                                   user follows.
     * @param int         $first         The maximum number of items to return per page in the response. The minimum
     *                                   page size is 1 item per page and the maximum is 100. The default is 20.
     * @param string|null $after         The cursor used to get the next page of results. The Pagination object in the
     *                                   response contains the cursor’s value.
     */
    public function __construct(
        public string $userId,
        public ?string $broadcasterId = null,
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
    }
}

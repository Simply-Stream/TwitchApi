<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Request;

use Webmozart\Assert\Assert;

final readonly class GetChannelFollowersRequest
{
    /**
     * @param string      $broadcasterId The broadcaster’s ID. Returns the list of users that follow this broadcaster.
     * @param string|null $userId        A user’s ID. Use this parameter to see whether the user follows this
     *                                   broadcaster. If specified, the response contains this user if they follow the
     *                                   broadcaster. If not specified, the response contains all users that follow the
     *                                   broadcaster.
     * @param int         $first         The maximum number of items to return per page in the response. The minimum
     *                                   page size is 1 item per page and the maximum is 100. The default is 20.
     * @param string|null $after         The cursor used to get the next page of results. The Pagination object in the
     *                                   response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public ?string $userId = null,
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
    }
}

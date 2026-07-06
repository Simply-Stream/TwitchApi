<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Request;

use Webmozart\Assert\Assert;

final readonly class GetFollowedStreamsRequest
{
    /**
     * @param string      $userId The ID of the user whose list of followed streams you want to get. This ID must match
     *                          the user ID in the access token.
     * @param int         $first  The maximum number of items to return per page in the response. The minimum page size
     *                          is 1 item per page and the maximum is 100 items per page. The default is 100.
     * @param string|null $after  The cursor used to get the next page of results. The Pagination object in the response
     *                          contains the cursor’s value.
     */
    public function __construct(
        public string $userId,
        public int $first = 100,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
    }
}

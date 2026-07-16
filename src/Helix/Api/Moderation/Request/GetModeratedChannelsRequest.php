<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use Webmozart\Assert\Assert;

final readonly class GetModeratedChannelsRequest
{
    /**
     * @param string      $userId A user’s ID. Returns the list of channels that this user has moderator privileges in.
     *                          This ID must match the user ID in the user OAuth token.
     * @param string|null $after  The cursor used to get the next page of results. The Pagination object in the response
     *                          contains the cursor’s value.
     * @param int         $first  The maximum number of items to return per page in the response. Minimum page size is 1
     *                          item per page and the maximum is 100. The default is 20.
     */
    public function __construct(
        public string $userId,
        public ?string $after = null,
        public int $first = 20,
    ) {
        Assert::range($first, 1, 100);
    }
}

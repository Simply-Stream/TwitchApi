<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Moderation\Request;

use Webmozart\Assert\Assert;

final readonly class GetBlockedTermsRequest
{
    /**
     * @param string      $broadcasterId The ID of the broadcaster whose blocked terms you’re getting.
     * @param string      $moderatorId   The ID of the broadcaster or a user that has permission to moderate the
     *                                  broadcaster’s chat room. This ID must match the user ID in the user access
     *                                  token.
     * @param int         $first         The maximum number of items to return per page in the response. The minimum
     *                                  page size is 1 item per page and the maximum is 100 items per page. The default
     *                                  is 20.
     * @param string|null $after         The cursor used to get the next page of results. The Pagination object in the
     *                                  response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
    }
}

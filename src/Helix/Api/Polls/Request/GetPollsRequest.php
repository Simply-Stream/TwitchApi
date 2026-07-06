<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Polls\Request;

use Webmozart\Assert\Assert;

final readonly class GetPollsRequest
{
    /**
     * @param string       $broadcasterId The ID of the broadcaster that created the polls. This ID must match the user
     *                                    ID in the user access token.
     * @param list<string> $ids           A list of IDs that identify the polls to return. To specify more than one ID,
     *                                    include this parameter for each poll you want to get. For example,
     *                                    id=1234&id=5678. You may specify a maximum of 20 IDs.
     *
     *                                    Specify this parameter only if you want to filter the list that the request
     *                                    returns. The endpoint ignores duplicate IDs and those not owned by this
     *                                    broadcaster.
     * @param int          $first         The maximum number of items to return per page in the response. The minimum
     *                                    page size is 1 item per page and the maximum is 20 items per page. The default
     *                                    is 20.
     * @param string|null  $after         The cursor used to get the next page of results. The Pagination object in the
     *                                    response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public array $ids = [],
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 20);
        Assert::maxCount($ids, 20);
        Assert::allString($ids);
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Predictions\Request;

use Webmozart\Assert\Assert;

final readonly class GetPredictionsRequest
{
    /**
     * @param string       $broadcasterId The ID of the broadcaster whose predictions you want to get. This ID must
     *                                    match the user ID associated with the user access token.
     * @param list<string> $ids           The ID of the prediction to get. To specify more than one ID, include this
     *                                    parameter for each prediction you want to get. For example, id=1234&id=5678.
     *                                    You may specify a maximum of 25 IDs. The endpoint ignores duplicate IDs and
     *                                    those not owned by the broadcaster.
     * @param int          $first         The maximum number of items to return per page in the response. The minimum
     *                                    page size is 1 item per page and the maximum is 25 items per page. The default
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
        Assert::range($first, 1, 25);
        Assert::maxCount($ids, 25);
        Assert::allString($ids);
    }
}

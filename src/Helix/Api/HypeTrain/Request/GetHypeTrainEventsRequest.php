<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\HypeTrain\Request;

use Webmozart\Assert\Assert;

final readonly class GetHypeTrainEventsRequest
{
    /**
     * @param string      $broadcasterId The ID of the broadcaster that’s running the Hype Train. This ID must match the
     *                                   User ID in the user access token.
     * @param int         $first         The maximum number of items to return per page in the response. The minimum
     *                                   page size is 1 item per page and the maximum is 100 items per page. The default
     *                                   is 1.
     * @param string|null $after         The cursor used to get the next page of results. The Pagination object in the
     *                                   response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public int $first = 1,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
    }
}

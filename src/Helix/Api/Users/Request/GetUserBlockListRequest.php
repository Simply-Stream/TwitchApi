<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Users\Request;

use Webmozart\Assert\Assert;

final readonly class GetUserBlockListRequest
{
    /**
     * @param string      $broadcasterId The ID of the broadcaster whose list of blocked users you want to get.
     * @param int         $first         The maximum number of items to return per page in the response. The minimum
     *                                  page size is 1 item per page and the maximum is 100. The default is 20.
     * @param string|null $after         The cursor used to get the next page of results. The Pagination object in the
     *                                  response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Games\Request;

use Webmozart\Assert\Assert;

final readonly class GetTopGamesRequest
{
    /**
     * @param string|null $after  The cursor used to get the next page of results. The Pagination object in the response
     *                           contains the cursor’s value.
     * @param string|null $before The cursor used to get the previous page of results. The Pagination object in the
     *                           response contains the cursor’s value.
     * @param int         $first  The maximum number of items to return per page in the response. The minimum page size
     *                           is 1 item per page and the maximum is 100 items per page. The default is 20.
     */
    public function __construct(
        public ?string $after = null,
        public ?string $before = null,
        public int $first = 20,
    ) {
        Assert::range($first, 1, 100);
    }
}

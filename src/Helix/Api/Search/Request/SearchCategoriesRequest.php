<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Search\Request;

use Webmozart\Assert\Assert;

final readonly class SearchCategoriesRequest
{
    /**
     * @param string      $query The URI-encoded search string. For example, encode #archery as %23archery and search
     *                          strings like angel of death as angel%20of%20death.
     * @param int         $first The maximum number of items to return per page in the response. The minimum page size
     *                          is 1 item per page and the maximum is 100 items per page. The default is 20.
     * @param string|null $after The cursor used to get the next page of results. The Pagination object in the response
     *                          contains the cursor’s value.
     */
    public function __construct(
        public string $query,
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::stringNotEmpty($query, 'The search query can\'t be empty.');
        Assert::range($first, 1, 100);
    }
}

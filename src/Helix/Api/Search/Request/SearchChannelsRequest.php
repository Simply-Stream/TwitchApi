<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Search\Request;

use Webmozart\Assert\Assert;

final readonly class SearchChannelsRequest
{
    /**
     * @param string      $query    The URI-encoded search string. For example, encode search strings like angel of
     *                             death as angel%20of%20death.
     * @param bool        $liveOnly A Boolean value that determines whether the response includes only channels that are
     *                             currently streaming live. Set to true to get only channels that are streaming live;
     *                             otherwise, false to get live and offline channels. The default is false.
     * @param int         $first    The maximum number of items to return per page in the response. The minimum page
     *                             size is 1 item per page and the maximum is 100 items per page. The default is 20.
     * @param string|null $after    The cursor used to get the next page of results. The Pagination object in the
     *                             response contains the cursor’s value.
     */
    public function __construct(
        public string $query,
        public bool $liveOnly = false,
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::stringNotEmpty($query, 'The search query can\'t be empty.');
        Assert::range($first, 1, 100);
    }
}

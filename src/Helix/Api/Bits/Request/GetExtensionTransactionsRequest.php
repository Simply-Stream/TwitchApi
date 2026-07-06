<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Bits\Request;

use Webmozart\Assert\Assert;

final readonly class GetExtensionTransactionsRequest
{
    /**
     * @param string       $extensionId The ID of the extension whose list of transactions you want to get.
     * @param list<string> $ids         A transaction ID used to filter the list of transactions. Specify this
     *                                  parameter for each transaction you want to get. For example, id=1234&id=5678.
     *                                  You may specify a maximum of 100 IDs.
     * @param int          $first       The maximum number of items to return per page in the response. The minimum page
     *                                  size is 1 item per page and the maximum is 100 items per page. The default is 20.
     * @param string|null  $after       The cursor used to get the next page of results. The Pagination object in the
     *                                  response contains the cursor’s value.
     */
    public function __construct(
        public string $extensionId,
        public array $ids = [],
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
        Assert::maxCount($ids, 100);
        Assert::allString($ids);
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Entitlements\Request;

use SimplyStream\TwitchApi\Helix\Api\Entitlements\FulfillmentStatus;
use Webmozart\Assert\Assert;

final readonly class GetDropsEntitlementsRequest
{
    /**
     * @param list<string>           $ids               An ID that identifies the entitlement to get. Include this
     *                                                  parameter for each entitlement you want to get. For example,
     *                                                  id=1234&id=5678. You may specify a maximum of 100 IDs.
     * @param string|null            $userId            An ID that identifies a user that was granted entitlements.
     * @param string|null            $gameId            An ID that identifies a game that offered entitlements.
     * @param FulfillmentStatus|null $fulfillmentStatus The entitlement’s fulfillment status. Used to filter the list to
     *                                                  only those with the specified status.
     * @param string|null            $after             The cursor used to get the next page of results. The Pagination
     *                                                  object in the response contains the cursor’s value.
     * @param int                    $first             The maximum number of entitlements to return per page in the
     *                                                  response. The minimum page size is 1 entitlement per page and
     *                                                  the maximum is 1000. The default is 20.
     */
    public function __construct(
        public array $ids = [],
        public ?string $userId = null,
        public ?string $gameId = null,
        public ?FulfillmentStatus $fulfillmentStatus = null,
        public ?string $after = null,
        public int $first = 20,
    ) {
        Assert::range($first, 1, 1000);
        Assert::maxCount($ids, 100);
        Assert::allString($ids);
    }
}

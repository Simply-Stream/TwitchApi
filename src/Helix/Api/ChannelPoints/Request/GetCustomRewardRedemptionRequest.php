<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request;

use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\RedemptionSort;
use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\RedemptionStatus;
use Webmozart\Assert\Assert;

final readonly class GetCustomRewardRedemptionRequest
{
    /**
     * @param string                $broadcasterId The ID of the broadcaster that owns the custom reward. This ID must
     *                                            match the user ID found in the user OAuth token.
     * @param string                $rewardId      The ID that identifies the custom reward whose redemptions you want
     *                                            to get.
     * @param RedemptionStatus|null $status        The status of the redemptions to return. NOTE: This field is required
     *                                            only if you don’t specify the id query parameter.
     *
     *                                            NOTE: Canceled and fulfilled redemptions are returned for only a few
     *                                            days after they’re canceled or fulfilled.
     * @param list<string>          $ids           A list of IDs to filter the redemptions by. To specify more than one
     *                                            ID, include this parameter for each redemption you want to get. For
     *                                            example, id=1234&id=5678. You may specify a maximum of 50 IDs.
     *
     *                                            Duplicate IDs are ignored. The response contains only the IDs that
     *                                            were found. If none of the IDs were found, the response is 404 Not
     *                                            Found.
     * @param RedemptionSort        $sort          The order to sort redemptions by. The default is OLDEST.
     * @param string|null           $after         The cursor used to get the next page of results. The Pagination
     *                                            object in the response contains the cursor’s value.
     * @param int                   $first         The maximum number of redemptions to return per page in the response.
     *                                            The minimum page size is 1 redemption per page and the maximum is 50.
     *                                            The default is 20.
     */
    public function __construct(
        public string $broadcasterId,
        public string $rewardId,
        public ?RedemptionStatus $status = null,
        public array $ids = [],
        public RedemptionSort $sort = RedemptionSort::Oldest,
        public ?string $after = null,
        public int $first = 20,
    ) {
        Assert::range($first, 1, 50);
        Assert::maxCount($ids, 50);
        Assert::allString($ids);

        // Twitch requires a status filter whenever no explicit IDs are given.
        if ($ids === []) {
            Assert::notNull($status, 'status is required when no redemption ids are provided.');
        }
    }
}

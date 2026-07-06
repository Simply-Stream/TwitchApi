<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request;

use SimplyStream\TwitchApi\Helix\Api\ChannelPoints\RedemptionStatus;
use Webmozart\Assert\Assert;

final readonly class UpdateRedemptionStatusRequest
{
    /**
     * @param string           $broadcasterId The ID of the broadcaster that’s updating the redemption. This ID must
     *                                        match the user ID associated with the user OAuth token.
     * @param list<string>     $ids           A list of IDs that identify the redemptions to update. To specify more
     *                                        than one ID, include this parameter for each redemption you want to
     *                                        update. For example, id=1234&id=5678. You may specify a maximum of 50 IDs.
     * @param string           $rewardId      The ID that identifies the reward that’s been redeemed.
     * @param RedemptionStatus $status        The status to set the redemption to. Only UNFULFILLED redemptions may be
     *                                        updated; set to either FULFILLED or CANCELED.
     */
    public function __construct(
        public string $broadcasterId,
        public array $ids,
        public string $rewardId,
        public RedemptionStatus $status,
    ) {
        Assert::minCount($ids, 1);
        Assert::maxCount($ids, 50);
        Assert::allString($ids);
    }
}

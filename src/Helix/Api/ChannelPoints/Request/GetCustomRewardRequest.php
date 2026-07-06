<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\ChannelPoints\Request;

use Webmozart\Assert\Assert;

final readonly class GetCustomRewardRequest
{
    /**
     * @param string       $broadcasterId         The ID of the broadcaster whose custom rewards you want to get. This
     *                                            ID must match the user ID found in the OAuth token.
     * @param list<string> $ids                   A list of IDs to filter the rewards by. To specify more than one ID,
     *                                            include this parameter for each reward you want to get. For example,
     *                                            id=1234&id=5678. You may specify a maximum of 50 IDs. Duplicate IDs
     *                                            are ignored. The response contains only the IDs that were found. If
     *                                            none of the IDs were found, the response is 404 Not Found.
     * @param bool         $onlyManageableRewards A Boolean value that determines whether the response contains only the
     *                                            custom rewards that the app may manage (the app is identified by the
     *                                            ID in the Client-Id header). Set to true to get only the custom
     *                                            rewards that the app may manage. The default is false.
     */
    public function __construct(
        public string $broadcasterId,
        public array $ids = [],
        public bool $onlyManageableRewards = false,
    ) {
        Assert::maxCount($ids, 50);
        Assert::allString($ids);
    }
}

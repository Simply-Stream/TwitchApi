<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Channels\Request;

use Webmozart\Assert\Assert;

final readonly class GetChannelInformationRequest
{
    /**
     * @param list<string> $broadcasterIds The ID of the broadcaster whose channel you want to get. To specify more than
     *                                     one ID, include this parameter for each broadcaster you want to get. For
     *                                     example, broadcaster_id=1234&broadcaster_id=5678. You may specify a maximum of
     *                                     100 IDs. The API ignores duplicate IDs and IDs that are not found.
     */
    public function __construct(
        public array $broadcasterIds,
    ) {
        Assert::minCount($broadcasterIds, 1);
        Assert::maxCount($broadcasterIds, 100);
        Assert::allString($broadcasterIds);
    }
}

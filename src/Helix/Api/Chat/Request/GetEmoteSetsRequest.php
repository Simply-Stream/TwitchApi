<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

use Webmozart\Assert\Assert;

final readonly class GetEmoteSetsRequest
{
    /**
     * @param list<string> $emoteSetIds An ID that identifies the emote set to get. Include this parameter for each
     *                                  emote set you want to get. For example, emote_set_id=1234&emote_set_id=5678. You
     *                                  may specify a maximum of 25 IDs. The response contains only the IDs that were
     *                                  found and ignores duplicate IDs.
     *
     *                                  To get emote set IDs, use the Get Channel Emotes API.
     */
    public function __construct(
        public array $emoteSetIds,
    ) {
        Assert::minCount($emoteSetIds, 1);
        Assert::maxCount($emoteSetIds, 25);
        Assert::allString($emoteSetIds);
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

use Webmozart\Assert\Assert;

final readonly class GetChattersRequest
{
    /**
     * @param string      $broadcasterId The ID of the broadcaster whose list of chatters you want to get.
     * @param string      $moderatorId   The ID of the broadcaster or one of the broadcaster’s moderators. This ID must
     *                                   match the user ID in the user access token.
     * @param int         $first         The maximum number of items to return per page in the response. The minimum
     *                                   page size is 1 item per page and the maximum is 1,000. The default is 100.
     * @param string|null $after         The cursor used to get the next page of results. The Pagination object in the
     *                                   response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public string $moderatorId,
        public int $first = 100,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 1000);
    }
}

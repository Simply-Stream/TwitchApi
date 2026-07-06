<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Request;

use Webmozart\Assert\Assert;

final readonly class GetUserChatColorRequest
{
    /**
     * @param list<string> $userIds The ID of the user whose username color you want to get. To specify more than one
     *                             user, include the user_id parameter for each user to get. For example,
     *                             &user_id=1234&user_id=5678. The maximum number of IDs that you may specify is 100.
     *
     *                             The API ignores duplicate IDs and IDs that weren’t found.
     */
    public function __construct(
        public array $userIds,
    ) {
        Assert::minCount($userIds, 1);
        Assert::maxCount($userIds, 100);
        Assert::allString($userIds);
    }
}

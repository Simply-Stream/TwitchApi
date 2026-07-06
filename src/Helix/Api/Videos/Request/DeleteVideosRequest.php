<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Videos\Request;

use Webmozart\Assert\Assert;

final readonly class DeleteVideosRequest
{
    /**
     * @param list<string> $ids The list of videos to delete. To specify more than one video, include the id parameter
     *                         for each video to delete. For example, id=1234&id=5678. You can delete a maximum of 5
     *                         videos per request. Ignores invalid video IDs.
     *
     *                         If the user doesn’t have permission to delete one of the videos in the list, none of the
     *                         videos are deleted.
     */
    public function __construct(
        public array $ids,
    ) {
        Assert::minCount($ids, 1);
        Assert::maxCount($ids, 5);
        Assert::allString($ids);
    }
}

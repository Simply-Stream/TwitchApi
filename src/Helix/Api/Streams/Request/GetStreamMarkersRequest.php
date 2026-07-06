<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Streams\Request;

use Webmozart\Assert\Assert;

final readonly class GetStreamMarkersRequest
{
    /**
     * @param string|null $userId  A user ID. The request returns the markers from this user’s most recent video. This
     *                            ID must match the user ID in the access token or the user in the access token must be
     *                            one of the broadcaster’s editors.
     *
     *                            This parameter and the video_id query parameter are mutually exclusive.
     * @param string|null $videoId A video on demand (VOD)/video ID. The request returns the markers from this
     *                            VOD/video. The user in the access token must own the video or the user must be one of
     *                            the broadcaster’s editors.
     *
     *                            This parameter and the user_id query parameter are mutually exclusive.
     * @param int         $first   The maximum number of items to return per page in the response. The minimum page size
     *                            is 1 item per page and the maximum is 100 items per page. The default is 20.
     * @param string|null $before  The cursor used to get the previous page of results. The Pagination object in the
     *                            response contains the cursor’s value.
     * @param string|null $after   The cursor used to get the next page of results. The Pagination object in the response
     *                            contains the cursor’s value.
     */
    public function __construct(
        public ?string $userId = null,
        public ?string $videoId = null,
        public int $first = 20,
        public ?string $before = null,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 100);

        // user_id and video_id are mutually exclusive; exactly one must be provided.
        Assert::false(
            $userId === null && $videoId === null,
            'Either userId or videoId must be provided.',
        );
        Assert::false(
            $userId !== null && $videoId !== null,
            'userId and videoId are mutually exclusive; provide only one.',
        );
    }
}

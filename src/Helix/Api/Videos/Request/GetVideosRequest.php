<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Videos\Request;

use SimplyStream\TwitchApi\Helix\Api\Videos\VideoPeriod;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoSort;
use SimplyStream\TwitchApi\Helix\Api\Videos\VideoType;
use Webmozart\Assert\Assert;

final readonly class GetVideosRequest
{
    /**
     * @param list<string>     $ids      A list of IDs that identify the videos you want to get. To get more than one
     *                                  video, include this parameter for each video you want to get. For example,
     *                                  id=1234&id=5678. You may specify a maximum of 100 IDs. The endpoint ignores
     *                                  duplicate IDs and IDs that weren’t found (if there’s at least one valid ID).
     *
     *                                  The id, user_id, and game_id parameters are mutually exclusive.
     * @param string|null      $userId   The ID of the user whose list of videos you want to get.
     *
     *                                  The id, user_id, and game_id parameters are mutually exclusive.
     * @param string|null      $gameId   A category or game ID. The response contains a maximum of 500 videos that show
     *                                  this content. To get category/game IDs, use the Search Categories endpoint.
     *
     *                                  The id, user_id, and game_id parameters are mutually exclusive.
     * @param string|null      $language A filter used to filter the list of videos by the language that the video owner
     *                                  broadcasts in. For example, to get videos that were broadcast in German, set
     *                                  this parameter to the ISO 639-1 two-letter code for German (i.e., DE). For a
     *                                  list of supported languages, see Supported Stream Language. If the language is
     *                                  not supported, use “other.”
     *
     *                                  Specify this parameter only if you specify the game_id query parameter.
     * @param VideoPeriod      $period   A filter used to filter the list of videos by when they were published. For
     *                                  example, videos published in the last week. The default is “all,” which returns
     *                                  videos published in all periods.
     *
     *                                  Specify this parameter only if you specify the game_id or user_id query
     *                                  parameter.
     * @param VideoSort        $sort     The order to sort the returned videos in. The default is “time.”
     *
     *                                  Specify this parameter only if you specify the game_id or user_id query
     *                                  parameter.
     * @param VideoType        $type     A filter used to filter the list of videos by the video’s type. The default is
     *                                  “all,” which returns all video types.
     *
     *                                  Specify this parameter only if you specify the game_id or user_id query
     *                                  parameter.
     * @param int              $first    The maximum number of items to return per page in the response. The minimum
     *                                  page size is 1 item per page and the maximum is 100. The default is 20.
     *
     *                                  Specify this parameter only if you specify the game_id or user_id query
     *                                  parameter.
     * @param string|null      $after    The cursor used to get the next page of results. The Pagination object in the
     *                                  response contains the cursor’s value.
     *
     *                                  Specify this parameter only if you specify the user_id query parameter.
     * @param string|null      $before   The cursor used to get the previous page of results. The Pagination object in
     *                                  the response contains the cursor’s value.
     *
     *                                  Specify this parameter only if you specify the user_id query parameter.
     */
    public function __construct(
        public array $ids = [],
        public ?string $userId = null,
        public ?string $gameId = null,
        public ?string $language = null,
        public VideoPeriod $period = VideoPeriod::All,
        public VideoSort $sort = VideoSort::Time,
        public VideoType $type = VideoType::All,
        public int $first = 20,
        public ?string $after = null,
        public ?string $before = null,
    ) {
        Assert::range($first, 1, 100);
        Assert::maxCount($ids, 100);
        Assert::allString($ids);

        // id, user_id and game_id are mutually exclusive; exactly one must be provided.
        $primaryFilters = (int) ($ids !== []) + (int) ($userId !== null) + (int) ($gameId !== null);
        Assert::same(
            $primaryFilters,
            1,
            'Exactly one of ids, userId or gameId must be provided; they are mutually exclusive.',
        );
    }
}

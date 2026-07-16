<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Clips\Request;

use DateTimeInterface;
use Webmozart\Assert\Assert;

final readonly class GetClipsRequest
{
    /**
     * @param string|null            $broadcasterId An ID that identifies the broadcaster whose video clips you want to
     *                                              get. Use this parameter to get clips that were captured from the
     *                                              broadcaster’s streams.
     * @param string|null            $gameId        An ID that identifies the game whose clips you want to get. Use this
     *                                              parameter to get clips that were captured from streams that were
     *                                              playing this game.
     * @param list<string>           $ids           An ID that identifies the clip to get. To specify more than one ID,
     *                                              include this parameter for each clip you want to get. For example,
     *                                              id=foo&id=bar. You may specify a maximum of 100 IDs. The API ignores
     *                                              duplicate IDs and IDs that aren’t found.
     * @param DateTimeInterface|null $startedAt     The start date used to filter clips. The API returns only clips
     *                                              within the start and end date window. Specify the date and time in
     *                                              RFC3339 format.
     * @param DateTimeInterface|null $endedAt       The end date used to filter clips. If not specified, the time window
     *                                              is the start date plus one week. Specify the date and time in
     *                                              RFC3339 format.
     * @param int                    $first         The maximum number of clips to return per page in the response. The
     *                                              minimum page size is 1 clip per page and the maximum is 100. The
     *                                              default is 20.
     * @param string|null            $before        The cursor used to get the previous page of results. The Pagination
     *                                              object in the response contains the cursor’s value.
     * @param string|null            $after         The cursor used to get the next page of results. The Pagination
     *                                              object in the response contains the cursor’s value.
     * @param bool|null              $isFeatured    A Boolean value that determines whether the response includes
     *                                              featured clips. If true, returns only clips that are featured. If
     *                                              false, returns only clips that aren’t featured. All clips are
     *                                              returned if this parameter is not present.
     */
    public function __construct(
        public ?string $broadcasterId = null,
        public ?string $gameId = null,
        public array $ids = [],
        public ?DateTimeInterface $startedAt = null,
        public ?DateTimeInterface $endedAt = null,
        public int $first = 20,
        public ?string $before = null,
        public ?string $after = null,
        public ?bool $isFeatured = null,
    ) {
        Assert::range($first, 1, 100);
        Assert::maxCount($ids, 100);
        Assert::allString($ids);

        // Twitch requires exactly one primary filter: broadcaster, game, or clip ids.
        Assert::true(
            $broadcasterId !== null || $gameId !== null || $ids !== [],
            'You need to specify at least one of broadcasterId, gameId or ids.',
        );
    }
}

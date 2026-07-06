<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Request;

use DateTimeInterface;
use Webmozart\Assert\Assert;

final readonly class GetChannelStreamScheduleRequest
{
    /**
     * @param string                 $broadcasterId The ID of the broadcaster that owns the streaming schedule you want
     *                                              to get.
     * @param list<string>           $ids           The ID of the scheduled segment to return. To specify more than one
     *                                              segment, include the ID of each segment you want to get. For example,
     *                                              id=1234&id=5678. You may specify a maximum of 100 IDs.
     * @param DateTimeInterface|null $startTime     The UTC date and time that identifies when in the broadcaster’s
     *                                              schedule to start returning segments. If not specified, the request
     *                                              returns segments starting after the current UTC date and time.
     *                                              Specify the date and time in RFC3339 format (for example,
     *                                              2022-09-01T00:00:00Z).
     * @param int                    $first         The maximum number of items to return per page in the response. The
     *                                              minimum page size is 1 item per page and the maximum is 25 items per
     *                                              page. The default is 20.
     * @param string|null            $after         The cursor used to get the next page of results. The Pagination
     *                                              object in the response contains the cursor’s value.
     */
    public function __construct(
        public string $broadcasterId,
        public array $ids = [],
        public ?DateTimeInterface $startTime = null,
        public int $first = 20,
        public ?string $after = null,
    ) {
        Assert::range($first, 1, 25);
        Assert::maxCount($ids, 100);
        Assert::allString($ids);
    }
}

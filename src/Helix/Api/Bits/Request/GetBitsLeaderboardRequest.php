<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Bits\Request;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Api\Bits\BitsLeaderboardPeriod;
use Webmozart\Assert\Assert;

final readonly class GetBitsLeaderboardRequest
{
    /**
     * @param int                     $count     The number of results to return. The minimum count is 1 and the
     *                                           maximum is 100. The default is 10.
     * @param BitsLeaderboardPeriod   $period    The time period over which data is aggregated (uses the PST time
     *                                           zone). Possible values are:
     *
     *                                           - day — A day spans from 00:00:00 on the day specified in started_at
     *                                           and runs through 00:00:00 of the next day.
     *                                           - week — A week spans from 00:00:00 on the Monday of the week specified
     *                                           in started_at and runs through 00:00:00 of the next Monday.
     *                                           - month — A month spans from 00:00:00 on the first day of the month
     *                                           specified in started_at and runs through 00:00:00 of the first day of
     *                                           the next month.
     *                                           - year — A year spans from 00:00:00 on the first day of the year
     *                                           specified in started_at and runs through 00:00:00 of the first day of
     *                                           the next year.
     *                                           - all — Default. The lifetime of the broadcaster's channel.
     * @param DateTimeInterface|null  $startedAt The start date, in RFC3339 format, used for determining the aggregation
     *                                           period. Specify this parameter only if you specify the period query
     *                                           parameter. The start date is ignored if period is all.
     *
     *                                           Note that the date is converted to PST before being used, so if you set
     *                                           the start time to 2022-01-01T00:00:00.0Z and period to month, the actual
     *                                           reporting period is December 2021, not January 2022. If you want the
     *                                           reporting period to be January 2022, you must set the start time to
     *                                           2022-01-01T08:00:00.0Z or 2022-01-01T00:00:00.0-08:00.
     *
     *                                           If your start date uses the ‘+’ offset operator (for example,
     *                                           2022-01-01T00:00:00.0+05:00), you must URL encode the start date.
     * @param string|null             $userId    An ID that identifies a user that cheered bits in the channel. If count
     *                                           is greater than 1, the response may include users ranked above and
     *                                           below the specified user. To get the leaderboard’s top leaders, don’t
     *                                           specify a user ID.
     */
    public function __construct(
        public int $count = 10,
        public BitsLeaderboardPeriod $period = BitsLeaderboardPeriod::All,
        public ?DateTimeInterface $startedAt = null,
        public ?string $userId = null,
    ) {
        Assert::range($count, 1, 100);
    }
}

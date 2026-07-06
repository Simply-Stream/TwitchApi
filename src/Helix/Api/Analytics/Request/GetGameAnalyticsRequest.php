<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Analytics\Request;

use DateTimeInterface;
use Webmozart\Assert\Assert;

final class GetGameAnalyticsRequest
{
    /**
     * @param string|null            $gameId    The game’s client ID. If specified, the response contains a report for
     *                                          the specified game. If not specified, the response includes a report
     *                                          for each of the authenticated user’s games.
     * @param string|null            $type      The type of analytics report to get. Possible values are:
     *                                          overview_v2
     * @param DateTimeInterface|null $startedAt The reporting window’s start date, in RFC3339 format. Set the time
     *                                          portion to zeroes (for example, 2021-10-22T00:00:00Z). If you specify a
     *                                          start date, you must specify an end date.
     *
     *                                          The start date must be within one year of today’s date. If you specify
     *                                          an earlier date, the API ignores it and uses a date that’s one year
     *                                          prior to today’s date. If you don’t specify a start and end date, the
     *                                          report includes all available data for the last 365 days from today.
     *
     *                                          The report contains one row of data for each day in the reporting
     *                                          window.
     * @param DateTimeInterface|null $endedAt   The reporting window’s end date, in RFC3339 format. Set the time portion
     *                                          to zeroes (for example, 2021-10-22T00:00:00Z). The report is inclusive
     *                                          of the end date.
     *
     *                                          Specify an end date only if you provide a start date. Because it can
     *                                          take up to two days for the data to be available, you must specify an
     *                                          end date that’s earlier than today minus one to two days. If not, the
     *                                          API ignores your end date and uses an end date that is today minus one
     *                                          to two days.
     * @param int                    $first     The maximum number of report URLs to return per page in the response.
     *                                          The minimum page size is 1 URL per page and the maximum is 100 URLs per
     *                                          page. The default is 20.
     *
     *                                          NOTE: While you may specify a maximum value of 100, the response will
     *                                          contain at most 20 URLs per page.
     * @param string|null            $after     The cursor used to get the next page of results. The Pagination object
     *                                          in the response contains the cursor’s value.
     *
     *                                          This parameter is ignored if game_id parameter is set.
     */
    public function __construct(
        public readonly ?string $gameId = null,
        public readonly ?string $type = null,
        public readonly ?DateTimeInterface $startedAt = null,
        public readonly ?DateTimeInterface $endedAt = null,
        public readonly int $first = 20,
        public readonly ?string $after = null,
    ) {
        Assert::range($first, 1, 100);
    }
}

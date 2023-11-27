<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models;

use SimplyStream\TwitchApi\Helix\Models\Analytics\DateRange;

/**
 * @template T
 * @extends TwitchDataResponse<T>
 */
readonly class TwitchDateRangeDataResponse extends TwitchDataResponse
{
    /**
     * @param T              $data
     * @param DateRange|null $dateRange The reporting window’s start and end dates, in RFC3339 format. The dates are
     *                                  calculated by using the started_at and period query parameters. If you don’t
     *                                  specify the started_at query parameter, the fields contain empty strings.
     * @param int|null       $total     The number of ranked users in data. This is the value in the count query
     *                                  parameter or the total number of entries on the leaderboard, whichever is less.
     */
    public function __construct(
        mixed $data,
        protected ?DateRange $dateRange = null,
        protected ?int $total = null,
    ) {
        parent::__construct($data);
    }

    public function getDateRange(): ?DateRange
    {
        return $this->dateRange;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }
}

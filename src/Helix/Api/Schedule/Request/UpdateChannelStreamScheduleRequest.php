<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Schedule\Request;

use DateTimeInterface;

final readonly class UpdateChannelStreamScheduleRequest
{
    /**
     * @param string                 $broadcasterId     The ID of the broadcaster whose schedule settings you want to
     *                                                  update. The ID must match the user ID in the user access token.
     * @param bool                   $isVacationEnabled A Boolean value that indicates whether the broadcaster has
     *                                                  scheduled a vacation. Set to true to enable Vacation Mode and add
     *                                                  vacation dates, or false to cancel a previously scheduled
     *                                                  vacation.
     * @param DateTimeInterface|null $vacationStartTime The UTC date and time of when the broadcaster’s vacation starts.
     *                                                  Specify the date and time in RFC3339 format (for example,
     *                                                  2021-05-16T00:00:00Z). Required if is_vacation_enabled is true.
     * @param DateTimeInterface|null $vacationEndTime   The UTC date and time of when the broadcaster’s vacation ends.
     *                                                  Specify the date and time in RFC3339 format (for example,
     *                                                  2021-05-30T23:59:59Z). Required if is_vacation_enabled is true.
     * @param string|null            $timezone          The time zone that the broadcaster broadcasts from. Specify the
     *                                                  time zone using IANA time zone database format (for example,
     *                                                  America/New_York). Required if is_vacation_enabled is true.
     */
    public function __construct(
        public string $broadcasterId,
        public bool $isVacationEnabled = false,
        public ?DateTimeInterface $vacationStartTime = null,
        public ?DateTimeInterface $vacationEndTime = null,
        public ?string $timezone = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

use DateTimeInterface;

final readonly class ScheduleSegment
{
    /**
     * @param string                 $id            An ID that identifies this broadcast segment.
     * @param DateTimeInterface      $startTime     The UTC date and time (in RFC3339 format) of when the broadcast
     *                                              starts.
     * @param DateTimeInterface      $endTime       The UTC date and time (in RFC3339 format) of when the broadcast
     *                                              ends.
     * @param string                 $title         The broadcast segment’s title.
     * @param bool                   $isRecurring   A Boolean value that determines whether the broadcast is part of a
     *                                              recurring series that streams at the same time each week or is a
     *                                              one-time broadcast. Is true if the broadcast is part of a recurring
     *                                              series.
     * @param Category|null          $category      The type of content that the broadcaster plans to stream, or null
     *                                              if not specified.
     * @param DateTimeInterface|null $canceledUntil If this segment of a recurring broadcast was canceled, the UTC date
     *                                              and time (in RFC3339 format) up to which it’s cancelled; otherwise,
     *                                              null.
     */
    public function __construct(
        public string $id,
        public DateTimeInterface $startTime,
        public DateTimeInterface $endTime,
        public string $title,
        public bool $isRecurring,
        public ?Category $category = null,
        public ?DateTimeInterface $canceledUntil = null,
    ) {
    }
}

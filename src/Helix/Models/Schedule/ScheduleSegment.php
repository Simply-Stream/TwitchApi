<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Schedule;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class ScheduleSegment
{
    use SerializesModels;

    /**
     * @param string                 $id                     An ID that identifies this broadcast segment.
     * @param DateTimeInterface      $startTime              The UTC date and time (in RFC3339 format) of when the
     *                                                       broadcast starts.
     * @param DateTimeInterface      $endTime                The UTC date and time (in RFC3339 format) of when the
     *                                                       broadcast ends.
     * @param string                 $title                  The broadcast segment’s title.
     *                                                       broadcast. If the broadcaster canceled this segment, this
     *                                                       field is set to the same value that’s in the end_time
     *                                                       field; otherwise, it’s set to null.
     * @param bool                   $isRecurring            A Boolean value that determines whether the broadcast is
     *                                                       part of a recurring series that streams at the same time
     *                                                       each week or is a one-time broadcast. Is true if the
     *                                                       broadcast is part of a recurring series.
     * @param Category|null          $category               The type of content that the broadcaster plans to stream
     *                                                       or null if not specified.The type of content that the
     *                                                       broadcaster plans to stream or null if not specified.
     * @param DateTimeInterface|null $canceledUntil          Indicates whether the broadcaster canceled this segment of
     *                                                       a recurring
     */
    public function __construct(
        private string $id,
        private DateTimeInterface $startTime,
        private DateTimeInterface $endTime,
        private string $title,
        private bool $isRecurring,
        private ?Category $category = null,
        private ?DateTimeInterface $canceledUntil = null
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStartTime(): DateTimeInterface
    {
        return $this->startTime;
    }

    public function getEndTime(): DateTimeInterface
    {
        return $this->endTime;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isRecurring(): bool
    {
        return $this->isRecurring;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function getCanceledUntil(): ?DateTimeInterface
    {
        return $this->canceledUntil;
    }
}

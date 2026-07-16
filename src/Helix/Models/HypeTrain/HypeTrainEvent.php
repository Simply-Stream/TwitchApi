<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\HypeTrain;

use DateTimeInterface;

final readonly class HypeTrainEvent
{
    /**
     * @param string            $id             An ID that identifies this event.
     * @param string            $eventType      The type of event. The string is in the form, hypetrain.{event_name}.
     *                                          The request returns only progress event types (i.e.,
     *                                          hypetrain.progression).
     * @param DateTimeInterface $eventTimestamp The UTC date and time (in RFC3339 format) that the event occurred.
     * @param string            $version        The version number of the definition of the event’s data. For example,
     *                                          the value is 1 if the data in event_data uses the first definition of
     *                                          the event’s data.
     * @param EventData         $eventData      The event’s data.
     */
    public function __construct(
        public string $id,
        public string $eventType,
        public DateTimeInterface $eventTimestamp,
        public string $version,
        public EventData $eventData,
    ) {
    }
}

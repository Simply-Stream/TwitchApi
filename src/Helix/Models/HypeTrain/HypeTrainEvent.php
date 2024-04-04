<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\HypeTrain;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class HypeTrainEvent
{
    use SerializesModels;

    /**
     * @param string            $id              An ID that identifies this event.
     * @param string            $eventType       The type of event. The string is in the form, hypetrain.{event_name}.
     *                                           The request returns only progress event types (i.e.,
     *                                           hypetrain.progression).
     * @param DateTimeInterface $eventTimestamp  The UTC date and time (in RFC3339 format) that the event occurred.
     * @param string            $version         The version number of the definition of the event’s data. For example,
     *                                           the value is 1 if the data in event_data uses the first definition of
     *                                           the event’s data.
     * @param EventData         $eventData       The event’s data.
     */
    public function __construct(
        private string $id,
        private string $eventType,
        private DateTimeInterface $eventTimestamp,
        private string $version,
        private EventData $eventData
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getEventTimestamp(): DateTimeInterface
    {
        return $this->eventTimestamp;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getEventData(): EventData
    {
        return $this->eventData;
    }
}

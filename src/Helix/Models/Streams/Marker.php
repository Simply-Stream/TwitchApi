<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

use DateTimeInterface;

final readonly class Marker
{
    /**
     * @param string            $id              An ID that identifies this marker.
     * @param DateTimeInterface $createdAt       The UTC date and time (in RFC3339 format) of when the user created the
     *                                           marker.
     * @param int               $positionSeconds The relative offset (in seconds) of the marker from the beginning of
     *                                           the stream.
     * @param string            $description     A description that the user gave the marker to help them remember why
     *                                           they marked the location.
     * @param string|null       $url             A URL that opens the video in Twitch Highlighter.
     */
    public function __construct(
        public string $id,
        public DateTimeInterface $createdAt,
        public int $positionSeconds,
        public string $description,
        public ?string $url = null,
    ) {
    }
}

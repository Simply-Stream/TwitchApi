<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

use DateTimeInterface;
use SimplyStream\TwitchApi\Helix\Models\SerializesModels;

final readonly class Marker
{
    use SerializesModels;

    /**
     * @param string            $id               An ID that identifies this marker.
     * @param DateTimeInterface $createdAt        The UTC date and time (in RFC3339 format) of when the user created
     *                                            the marker.
     * @param int               $positionSeconds  The relative offset (in seconds) of the marker from the beginning of
     *                                            the stream.
     * @param string            $description      A description that the user gave the marker to help them remember why
     *                                            they marked the location.
     * @param string|null       $url              A URL that opens the video in Twitch Highlighter.
     */
    public function __construct(
        private string $id,
        private DateTimeInterface $createdAt,
        private int $positionSeconds,
        private string $description,
        private ?string $url = null
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getPositionSeconds(): int
    {
        return $this->positionSeconds;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}

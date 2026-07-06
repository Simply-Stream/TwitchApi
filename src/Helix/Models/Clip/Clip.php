<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Clip;

use DateTimeInterface;

final readonly class Clip
{
    /**
     * @param string            $id              An ID that uniquely identifies the clip.
     * @param string            $url             A URL to the clip.
     * @param string            $embedUrl        A URL that you can use in an iframe to embed the clip
     * @param string            $broadcasterId   An ID that identifies the broadcaster that the video was clipped
     *                                           from.
     * @param string            $broadcasterName The broadcaster’s display name.
     * @param string            $creatorId       An ID that identifies the user that created the clip.
     * @param string            $creatorName     The user’s display name.
     * @param string            $videoId         An ID that identifies the video that the clip came from. This field
     *                                           contains an empty string if the video is not available.
     * @param string            $gameId          The ID of the game that was being played when the clip was created.
     * @param string            $language        The ISO 639-1 two-letter language code that the broadcaster
     *                                           broadcasts in. For example, en for English. The value is other if the
     *                                           broadcaster uses a language that Twitch doesn’t support.
     * @param string            $title           The title of the clip.
     * @param int               $viewCount       The number of times the clip has been viewed.
     * @param DateTimeInterface $createdAt       The date and time of when the clip was created. The date and time is
     *                                           in RFC3339 format.
     * @param string            $thumbnailUrl    A URL to a thumbnail image of the clip.
     * @param float             $duration        The length of the clip, in seconds. Precision is 0.1.
     * @param bool              $isFeatured      A Boolean value that indicates if the clip is featured or not.
     * @param int|null          $vodOffset       The zero-based offset, in seconds, to where the clip starts in the
     *                                           video (VOD). Is null if the video is not available or hasn’t been
     *                                           created yet from the live stream (see video_id).
     *
     *                                           Note that there’s a delay between when a clip is created during a
     *                                           broadcast and when the offset is set. During the delay period,
     *                                           vod_offset is null. The delay is indeterminant but is typically
     *                                           minutes long.
     */
    public function __construct(
        public string $id,
        public string $url,
        public string $embedUrl,
        public string $broadcasterId,
        public string $broadcasterName,
        public string $creatorId,
        public string $creatorName,
        public string $videoId,
        public string $gameId,
        public string $language,
        public string $title,
        public int $viewCount,
        public DateTimeInterface $createdAt,
        public string $thumbnailUrl,
        public float $duration,
        public bool $isFeatured,
        public ?int $vodOffset = null,
    ) {
    }
}

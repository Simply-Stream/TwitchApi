<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Videos;

use DateTimeInterface;

final readonly class Video
{
    /**
     * @param string            $id           An ID that identifies the video.
     * @param string|null       $streamId     The ID of the stream that the video originated from if the video’s type
     *                                        is “archive;” otherwise, null.
     * @param string            $userId       The ID of the broadcaster that owns the video.
     * @param string            $userLogin    The broadcaster’s login name.
     * @param string            $userName     The broadcaster’s display name.
     * @param string            $title        The video’s title.
     * @param string            $description  The video’s description.
     * @param DateTimeInterface $createdAt    The date and time, in UTC, of when the video was created. The timestamp
     *                                        is in RFC3339 format.
     * @param DateTimeInterface $publishedAt  The date and time, in UTC, of when the video was published. The timestamp
     *                                        is in RFC3339 format.
     * @param string            $url          The video’s URL.
     * @param string            $thumbnailUrl A URL to a thumbnail image of the video. Before using the URL, you must
     *                                        replace the %{width} and %{height} placeholders with the width and height
     *                                        of the thumbnail you want returned.
     * @param string            $viewable     The video’s viewable state. Always set to public.
     * @param int               $viewCount    The number of times that users have watched the video.
     * @param string            $language     The ISO 639-1 two-letter language code that the video was broadcast in.
     *                                        The value is “other” if the video was broadcast in a language not in the
     *                                        list of supported languages.
     * @param string            $type         The video’s type. Possible values are:
     *                                        - archive — An on-demand video (VOD) of one of the broadcaster's past
     *                                        streams.
     *                                        - highlight — A highlight reel of one of the broadcaster's past streams.
     *                                        - upload — A video that the broadcaster uploaded to their video library.
     * @param string            $duration     The video’s length in ISO 8601 duration format. For example, 3m21s
     *                                        represents 3 minutes, 21 seconds.
     * @param list<MutedSegment>|null $mutedSegments The segments that Twitch Audio Recognition muted; otherwise, null.
     */
    public function __construct(
        public string $id,
        public ?string $streamId,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $title,
        public string $description,
        public DateTimeInterface $createdAt,
        public DateTimeInterface $publishedAt,
        public string $url,
        public string $thumbnailUrl,
        public string $viewable,
        public int $viewCount,
        public string $language,
        public string $type,
        public string $duration,
        public ?array $mutedSegments = null,
    ) {
    }
}

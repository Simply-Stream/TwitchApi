<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Streams;

use DateTimeInterface;

final readonly class Stream
{
    /**
     * @param string             $id           An ID that identifies the stream. You can use this ID later to look up
     *                                         the video on demand (VOD).
     * @param string             $userId       The ID of the user that’s broadcasting the stream.
     * @param string             $userLogin    The user’s login name.
     * @param string             $userName     The user’s display name.
     * @param string             $gameId       The ID of the category or game being played.
     * @param string             $gameName     The name of the category or game being played.
     * @param string             $type         The type of stream. Possible values are:
     *                                         - live
     *                                         If an error occurs, this field is set to an empty string.
     * @param string             $title        The stream’s title. Is an empty string if not set.
     * @param list<string>       $tags         The tags applied to the stream.
     * @param int                $viewerCount  The number of users watching the stream.
     * @param DateTimeInterface  $startedAt    The UTC date and time (in RFC3339 format) of when the broadcast began.
     * @param string             $language     The language that the stream uses. This is an ISO 639-1 two-letter
     *                                         language code or other if the stream uses a language not in the list of
     *                                         supported stream languages.
     * @param string             $thumbnailUrl A URL to an image of a frame from the last 5 minutes of the stream.
     *                                         Replace the width and height placeholders in the URL
     *                                         ({width}x{height}) with the size of the image you want, in pixels.
     * @param bool               $isMature     A Boolean value that indicates whether the stream is meant for mature
     *                                         audiences.
     */
    public function __construct(
        public string $id,
        public string $userId,
        public string $userLogin,
        public string $userName,
        public string $gameId,
        public string $gameName,
        public string $type,
        public string $title,
        public array $tags,
        public int $viewerCount,
        public DateTimeInterface $startedAt,
        public string $language,
        public string $thumbnailUrl,
        public bool $isMature,
    ) {
    }
}

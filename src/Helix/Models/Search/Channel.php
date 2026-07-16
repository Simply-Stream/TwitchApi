<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Search;

use DateTimeInterface;

final readonly class Channel
{
    /**
     * @param string                 $broadcasterLanguage The ISO 639-1 two-letter language code of the language used
     *                                                    by the broadcaster. For example, en for English. If the
     *                                                    broadcaster uses a language not in the list of supported
     *                                                    stream languages, the value is other.
     * @param string                 $broadcasterLogin    The broadcaster’s login name.
     * @param string                 $displayName         The broadcaster’s display name.
     * @param string                 $id                  An ID that uniquely identifies the channel (this is the
     *                                                    broadcaster’s ID).
     * @param bool                   $isLive              A Boolean value that determines whether the broadcaster is
     *                                                    streaming live.
     * @param list<string>           $tags                The tags applied to the channel.
     * @param string                 $thumbnailUrl        A URL to a thumbnail of the broadcaster’s profile image.
     * @param string                 $title               The stream’s title. Is an empty string if the broadcaster
     *                                                    didn’t set it.
     * @param DateTimeInterface|null $startedAt           The UTC date and time (in RFC3339 format) of when the
     *                                                    broadcaster started streaming. Null if the broadcaster is not
     *                                                    streaming live.
     * @param string|null            $gameId              The ID of the game that the broadcaster is playing or last
     *                                                    played.
     * @param string|null            $gameName            The name of the game that the broadcaster is playing or last
     *                                                    played.
     */
    public function __construct(
        public string $broadcasterLanguage,
        public string $broadcasterLogin,
        public string $displayName,
        public string $id,
        public bool $isLive,
        public array $tags,
        public string $thumbnailUrl,
        public string $title,
        public ?DateTimeInterface $startedAt = null,
        public ?string $gameId = null,
        public ?string $gameName = null,
    ) {
    }
}

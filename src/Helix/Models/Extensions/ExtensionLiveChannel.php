<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

final readonly class ExtensionLiveChannel
{
    /**
     * @param string $broadcasterId   The ID of the broadcaster that is streaming live and has installed or activated
     *                                the extension.
     * @param string $broadcasterName The broadcaster’s display name.
     * @param string $gameName        The name of the category or game being streamed.
     * @param string $gameId          The ID of the category or game being streamed.
     * @param string $title           The title of the broadcaster’s stream. May be an empty string if not specified.
     */
    public function __construct(
        public string $broadcasterId,
        public string $broadcasterName,
        public string $gameName,
        public string $gameId,
        public string $title
    ) {
    }
}

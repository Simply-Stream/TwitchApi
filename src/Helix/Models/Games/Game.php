<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Games;

final readonly class Game
{
    /**
     * @param string $id        An ID that identifies the category or game.
     * @param string $name      The category’s or game’s name.
     * @param string $boxArtUrl A URL to the category’s or game’s box art. You must replace the {width}x{height}
     *                          placeholder with the size of image you want.
     * @param string $igdbId    The ID that IGDB uses to identify this game. If the IGDB ID is not available to Twitch,
     *                          this field is set to an empty string.
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $boxArtUrl,
        public string $igdbId,
    ) {
    }
}

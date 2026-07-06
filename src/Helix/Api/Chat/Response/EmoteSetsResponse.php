<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Response;

use SimplyStream\TwitchApi\Helix\Models\Chat\EmoteSet;

final readonly class EmoteSetsResponse
{
    /**
     * @param list<EmoteSet> $data
     * @param string         $template A templated URL. Use the values from the id, format, scale, and theme_mode fields
     *                                to replace the like-named placeholder strings in the templated URL to get the
     *                                emote’s URL.
     */
    public function __construct(
        public array $data,
        public string $template,
    ) {
    }
}

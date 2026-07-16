<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Search;

final readonly class Category
{
    /**
     * @param string $id        An ID that uniquely identifies the game or category.
     * @param string $name      The name of the game or category.
     * @param string $boxArtUrl A URL to an image of the game’s box art or streaming category.
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $boxArtUrl,
    ) {
    }
}

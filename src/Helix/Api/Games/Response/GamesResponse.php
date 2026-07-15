<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Games\Response;

use SimplyStream\TwitchApi\Helix\Models\Games\Game;

final readonly class GamesResponse
{
    /** @param list<Game> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

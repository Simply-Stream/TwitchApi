<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Games\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Games\Game;

final readonly class TopGamesResponse
{
    /** @param list<Game> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {}
}

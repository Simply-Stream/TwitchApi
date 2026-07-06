<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Chat\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Chat\Chatter;

final readonly class ChattersResponse
{
    /** @param list<Chatter> $data */
    public function __construct(
        public array $data,
        public int $total,
        public ?Pagination $pagination = null,
    ) {
    }
}

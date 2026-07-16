<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Videos\Response;

use SimplyStream\TwitchApi\Helix\Api\Pagination;
use SimplyStream\TwitchApi\Helix\Models\Videos\Video;

final readonly class VideosResponse
{
    /** @param list<Video> $data */
    public function __construct(
        public array $data,
        public ?Pagination $pagination = null,
    ) {
    }
}

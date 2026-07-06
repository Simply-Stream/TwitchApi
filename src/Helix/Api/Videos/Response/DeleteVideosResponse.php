<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Videos\Response;

final readonly class DeleteVideosResponse
{
    /** @param list<string> $data The IDs of the videos that were deleted. */
    public function __construct(
        public array $data,
    ) {}
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api;

final readonly class Pagination
{
    public function __construct(
        public ?string $cursor = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Bits\Response;

use SimplyStream\TwitchApi\Helix\Models\Bits\Cheermote;

final readonly class CheermotesResponse
{
    /** @param list<Cheermote> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

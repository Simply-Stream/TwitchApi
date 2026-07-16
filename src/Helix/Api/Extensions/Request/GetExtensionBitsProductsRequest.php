<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

final readonly class GetExtensionBitsProductsRequest
{
    /**
     * @param bool $shouldIncludeAll Whether to include disabled or expired Bits products in the response.
     */
    public function __construct(
        public bool $shouldIncludeAll = false,
    ) {
    }
}

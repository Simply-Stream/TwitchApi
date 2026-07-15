<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Response;

use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionBitsProduct;

final readonly class ExtensionBitsProductsResponse
{
    /** @param list<ExtensionBitsProduct> $data */
    public function __construct(
        public array $data,
    ) {
    }
}

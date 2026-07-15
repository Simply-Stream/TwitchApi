<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

final readonly class ExtensionBitsAmount
{
    /**
     * @param int    $amount The product’s price.
     * @param string $type   The type of currency. Possible values are:
     *                       - bits
     */
    public function __construct(
        public int $amount,
        public string $type
    ) {
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Events\ExtensionBitsTransaction;

final readonly class Product
{
    /**
     * @param string $name          Product name.
     * @param int    $bits          Bits involved in the transaction.
     * @param string $sku           Unique identifier for the product acquired.
     * @param bool   $inDevelopment Flag indicating if the product is in development. If in_development is true, bits
     *                              will be 0.
     */
    public function __construct(
        public string $name,
        public int $bits,
        public string $sku,
        public bool $inDevelopment
    ) {
    }
}

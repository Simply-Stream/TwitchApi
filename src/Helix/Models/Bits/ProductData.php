<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Bits;

final readonly class ProductData
{
    /**
     * @param string               $sku           An ID that identifies the digital product.
     * @param string               $domain        Set to twitch.ext. + <the extension's ID>.
     * @param array<string, mixed> $cost          Contains details about the digital product’s cost.
     * @param bool                 $inDevelopment A Boolean value that determines whether the product is in development.
     *                                            Is true if the digital product is in development and cannot be
     *                                            exchanged.
     * @param string               $displayName   The name of the digital product.
     * @param string               $expiration    This field is always empty since you may purchase only unexpired
     *                                            products.
     * @param bool                 $broadcast     A Boolean value that determines whether the data was broadcast to all
     *                                            instances of the extension. Is true if the data was broadcast to all
     *                                            instances.
     */
    public function __construct(
        public string $sku,
        public string $domain,
        public array $cost,
        public bool $inDevelopment,
        public string $displayName,
        public string $expiration,
        public bool $broadcast,
    ) {
    }
}

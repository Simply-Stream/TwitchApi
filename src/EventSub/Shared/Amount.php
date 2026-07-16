<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\EventSub\Shared;

final readonly class Amount
{
    /**
     * @param int    $value        The monetary amount in the currency’s minor unit (e.g. cents for USD, so $5.50 =
     *                            550).
     * @param int    $decimalPlace The number of decimal places used by the currency.
     * @param string $currency     The ISO-4217 three-letter currency code that identifies the type of currency in
     *                            value.
     */
    public function __construct(
        public int $value,
        public int $decimalPlace,
        public string $currency,
    ) {
    }
}

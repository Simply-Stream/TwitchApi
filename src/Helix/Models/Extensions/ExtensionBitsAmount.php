<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

use SimplyStream\TwitchApi\Helix\Models\SerializesModels;
use Webmozart\Assert\Assert;

final readonly class ExtensionBitsAmount
{
    use SerializesModels;

    /**
     * @param int    $amount The product’s price.
     * @param string $type   The type of currency. Possible values are:
     *                       - bits
     */
    public function __construct(
        private int $amount,
        private string $type
    ) {
        Assert::inArray($this->type, ['bits']);

        if ($this->type === 'bits') {
            Assert::greaterThanEq($this->amount, 1, 'The minimum price is %2$s, got %s');
            Assert::lessThanEq($this->amount, 10000, 'The minimum price is %2$s, got %s');
        }
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getType(): string
    {
        return $this->type;
    }
}

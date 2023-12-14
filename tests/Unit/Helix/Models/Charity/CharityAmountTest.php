<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Charity;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Charity\CharityAmount;

final class CharityAmountTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $value = 100;
        $decimalPlaces = 2;
        $currency = "USD";

        $charityAmount = new CharityAmount($value, $decimalPlaces, $currency);

        $this->assertEquals($value, $charityAmount->getValue());
        $this->assertEquals($decimalPlaces, $charityAmount->getDecimalPlaces());
        $this->assertEquals($currency, $charityAmount->getCurrency());

        $expectedArray = [
            'value' => $value,
            'decimal_places' => $decimalPlaces,
            'currency' => $currency
        ];

        $this->assertEquals($expectedArray, $charityAmount->toArray());
    }
}

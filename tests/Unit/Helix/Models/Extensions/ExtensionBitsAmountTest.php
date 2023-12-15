<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionBitsAmount;

class ExtensionBitsAmountTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $extensionBitsAmount = new ExtensionBitsAmount(1, 'bits');
        $this->assertEquals(1, $extensionBitsAmount->getAmount());
        $this->assertEquals('bits', $extensionBitsAmount->getType());
    }

    public function testConstructWithInvalidType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected one of: "bits". Got: "someInvalidType"');

        new ExtensionBitsAmount(1, 'someInvalidType');
    }

    public function testConstructWithInvalidAmountLessThan1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum price is 1, got 0');

        new ExtensionBitsAmount(0, 'bits');
    }

    public function testConstructWithInvalidAmountGreaterThan10000()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum price is 10000, got 10001');

        new ExtensionBitsAmount(10001, 'bits');
    }
}

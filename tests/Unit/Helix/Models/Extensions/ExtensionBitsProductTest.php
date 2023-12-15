<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionBitsAmount;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionBitsProduct;

final class ExtensionBitsProductTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $extensionBitsAmount = new ExtensionBitsAmount(100, "bits");
        $extensionBitsProduct = new ExtensionBitsProduct(
            "sku123",
            $extensionBitsAmount,
            true,
            "displayName",
            new DateTimeImmutable('2023-01-01'),
            true
        );

        $this->assertEquals("sku123", $extensionBitsProduct->getSku());
        $this->assertEquals($extensionBitsAmount, $extensionBitsProduct->getCost());
        $this->assertTrue($extensionBitsProduct->isInDevelopment());
        $this->assertEquals("displayName", $extensionBitsProduct->getDisplayName());
        $this->assertEquals(new DateTimeImmutable('2023-01-01'), $extensionBitsProduct->getExpiration());
        $this->assertTrue($extensionBitsProduct->isBroadcast());
    }
}

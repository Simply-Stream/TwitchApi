<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Bits;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Bits\ProductData;

final class ProductDataTest extends TestCase
{
    /**
     * Testing __construct method in ProductData class.
     */
    public function testConstruct(): void
    {
        $expectedSku = 'sku123';
        $expectedDomain = 'twitch.ext.123';
        $expectedCost = ['amount' => 100, 'currency' => 'USD'];
        $expectedInDevelopment = false;
        $expectedDisplayName = 'Test Product';
        $expectedExpiration = '';
        $expectedBroadcast = true;

        $productData = new ProductData(
            $expectedSku,
            $expectedDomain,
            $expectedCost,
            $expectedInDevelopment,
            $expectedDisplayName,
            $expectedExpiration,
            $expectedBroadcast
        );

        $this->assertInstanceOf(ProductData::class, $productData);
        $this->assertEquals($expectedSku, $productData->getSku());
        $this->assertEquals($expectedDomain, $productData->getDomain());
        $this->assertEquals($expectedCost, $productData->getCost());
        $this->assertEquals($expectedInDevelopment, $productData->isInDevelopment());
        $this->assertEquals($expectedDisplayName, $productData->getDisplayName());
        $this->assertEquals($expectedExpiration, $productData->getExpiration());
        $this->assertEquals($expectedBroadcast, $productData->isBroadcast());

        $this->assertIsArray($productData->toArray());
        $this->assertEquals([
            'sku' => $expectedSku,
            'domain' => $expectedDomain,
            'cost' => $expectedCost,
            'expiration' => $expectedExpiration,
            'broadcast' => $expectedBroadcast,
            'in_development' => $expectedInDevelopment,
            'display_name' => $expectedDisplayName,
        ], $productData->toArray());
    }
}

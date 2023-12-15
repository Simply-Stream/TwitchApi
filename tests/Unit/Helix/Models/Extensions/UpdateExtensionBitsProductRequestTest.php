<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionBitsAmount;
use SimplyStream\TwitchApi\Helix\Models\Extensions\UpdateExtensionBitsProductRequest;

class UpdateExtensionBitsProductRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $sku = 'test_sku';
        $cost = new ExtensionBitsAmount(123, 'bits');
        $displayName = 'Test Product';
        $inDevelopment = true;
        $expiration = new DateTimeImmutable('tomorrow');
        $isBroadcast = true;

        $request = new UpdateExtensionBitsProductRequest(
            $sku,
            $cost,
            $displayName,
            $inDevelopment,
            $expiration,
            $isBroadcast
        );

        $this->assertEquals($sku, $request->getSku());
        $this->assertSame($cost, $request->getCost());
        $this->assertEquals($displayName, $request->getDisplayName());
        $this->assertEquals($inDevelopment, $request->isInDevelopment());
        $this->assertEquals($expiration, $request->getExpiration());
        $this->assertEquals($isBroadcast, $request->isBroadcast());
    }

    public function testCanBeInitializedWithDefaultValues()
    {
        $sku = 'test_sku';
        $cost = new ExtensionBitsAmount(123, 'bits');
        $displayName = 'Test Product';

        $request = new UpdateExtensionBitsProductRequest(
            $sku,
            $cost,
            $displayName
        );

        $this->assertEquals($sku, $request->getSku());
        $this->assertSame($cost, $request->getCost());
        $this->assertEquals($displayName, $request->getDisplayName());
        $this->assertFalse($request->isInDevelopment());
        $this->assertNull($request->getExpiration());
        $this->assertFalse($request->isBroadcast());
    }

    public function testCanBeInitializedWithDisplayNameMax255Length()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The maximum length of a display name is 255, got 256");

        $sku = 'test_sku';
        $cost = new ExtensionBitsAmount(123, 'bits');
        $displayName = str_repeat('a', 256);

        new UpdateExtensionBitsProductRequest(
            $sku,
            $cost,
            $displayName
        );
    }
}

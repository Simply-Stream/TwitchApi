<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Bits;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Bits\ExtensionTransactions;
use SimplyStream\TwitchApi\Helix\Models\Bits\ProductData;

final class ExtensionTransactionsTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = 'test_id';
        $timestamp = new DateTimeImmutable();
        $broadcasterId = 'test_broadcaster_id';
        $broadcasterLogin = 'test_broadcaster_login';
        $broadcasterName = 'test_broadcaster_name';
        $userId = 'test_user_id';
        $userLogin = 'test_user_login';
        $userName = 'test_user_name';
        $productType = 'BITS_IN_EXTENSION';
        $productData = new ProductData('sku', 'domain', ['cost'], true, 'displayName', 'expiration', true);

        $extensionTransactions = new ExtensionTransactions(
            $id,
            $timestamp,
            $broadcasterId,
            $broadcasterLogin,
            $broadcasterName,
            $userId,
            $userLogin,
            $userName,
            $productType,
            $productData
        );

        $this->assertEquals($id, $extensionTransactions->getId());
        $this->assertEquals($timestamp, $extensionTransactions->getTimestamp());
        $this->assertEquals($broadcasterId, $extensionTransactions->getBroadcasterId());
        $this->assertEquals($broadcasterLogin, $extensionTransactions->getBroadcasterLogin());
        $this->assertEquals($broadcasterName, $extensionTransactions->getBroadcasterName());
        $this->assertEquals($userId, $extensionTransactions->getUserId());
        $this->assertEquals($userLogin, $extensionTransactions->getUserLogin());
        $this->assertEquals($userName, $extensionTransactions->getUserName());
        $this->assertEquals($productType, $extensionTransactions->getProductType());
        $this->assertEquals($productData, $extensionTransactions->getProductData());

        $this->assertIsArray($extensionTransactions->toArray());
        $this->assertEquals([
            'id' => $id,
            'timestamp' => $timestamp->format(DATE_RFC3339_EXTENDED),
            'broadcaster_id' => $broadcasterId,
            'broadcaster_login' => $broadcasterLogin,
            'broadcaster_name' => $broadcasterName,
            'user_id' => $userId,
            'user_login' => $userLogin,
            'user_name' => $userName,
            'product_type' => $productType,
            'product_data' => $productData->toArray(),
        ], $extensionTransactions->toArray());
    }
}

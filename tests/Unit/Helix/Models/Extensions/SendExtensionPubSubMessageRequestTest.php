<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SendExtensionPubSubMessageRequest;

final class SendExtensionPubSubMessageRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $target = ['global'];
        $message = 'This is a test message';
        $isGlobalBroadcast = false;
        $broadcasterId = '123456';

        $instance = new SendExtensionPubSubMessageRequest($target, $message, $isGlobalBroadcast, $broadcasterId);

        $this->assertSame($target, $instance->getTarget());
        $this->assertSame($message, $instance->getMessage());
        $this->assertSame($isGlobalBroadcast, $instance->isGlobalBroadcast());
        $this->assertSame($broadcasterId, $instance->getBroadcasterId());
    }

    public function testCanBeInitializedWithInvalidTargetThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Target got an invalid value. Possible values are: broadcast, global, whisper-USER_ID. Got "invalid"');

        $target = ['invalid'];
        $message = 'This is a test message';
        $isGlobalBroadcast = false;
        $broadcasterId = '123456';

        new SendExtensionPubSubMessageRequest($target, $message, $isGlobalBroadcast, $broadcasterId);
    }

    public function testCanBeInitializedGlobalBroadcastWithoutBroadcasterIdCreatesInstanceCorrectly()
    {
        $target = ['global'];
        $message = 'This is a test message';
        $isGlobalBroadcast = true;

        $instance = new SendExtensionPubSubMessageRequest($target, $message, $isGlobalBroadcast);

        $this->assertSame($target, $instance->getTarget());
        $this->assertSame($message, $instance->getMessage());
        $this->assertSame($isGlobalBroadcast, $instance->isGlobalBroadcast());
        $this->assertNull($instance->getBroadcasterId());
    }

    public function testCanBeInitializedGlobalBroadcastWithBroadcasterIdThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Broadcaster ID should not be included, when isGlobalBroadcast is set to true');

        $target = ['global'];
        $message = 'This is a test message';
        $isGlobalBroadcast = true;
        $broadcasterId = '123456';

        new SendExtensionPubSubMessageRequest($target, $message, $isGlobalBroadcast, $broadcasterId);
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Channels;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Channels\FollowedChannel;

class FollowedChannelTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = '123456';
        $broadcasterLogin = 'broadcasterLogin';
        $broadcasterName = 'broadcasterName';
        $followedAt = new DateTimeImmutable();

        $followedChannel = new FollowedChannel(
            $broadcasterId,
            $broadcasterLogin,
            $broadcasterName,
            $followedAt
        );

        $this->assertEquals($broadcasterId, $followedChannel->getBroadcasterId());
        $this->assertEquals($broadcasterLogin, $followedChannel->getBroadcasterLogin());
        $this->assertEquals($broadcasterName, $followedChannel->getBroadcasterName());
        $this->assertEquals($followedAt, $followedChannel->getFollowedAt());

        $this->assertIsArray($followedChannel->toArray());

        $expectedArray = [
            'broadcaster_id' => $broadcasterId,
            'broadcaster_login' => $broadcasterLogin,
            'broadcaster_name' => $broadcasterName,
            'followed_at' => $followedAt->format(DATE_RFC3339_EXTENDED),
        ];

        $this->assertEquals($expectedArray, $followedChannel->toArray());
    }
}

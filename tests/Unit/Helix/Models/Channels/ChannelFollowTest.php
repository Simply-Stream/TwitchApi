<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Channels;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelFollow;

final class ChannelFollowTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $followDateTime = new DateTimeImmutable('2023-01-01 00:00:00');
        $userId = '123';
        $userLogin = 'test_user_login';
        $userName = 'test_user_name';

        $channelFollow = new ChannelFollow(
            $followDateTime,
            $userId,
            $userLogin,
            $userName
        );

        $this->assertSame($followDateTime, $channelFollow->getFollowedAt());
        $this->assertSame($userId, $channelFollow->getUserId());
        $this->assertSame($userLogin, $channelFollow->getUserLogin());
        $this->assertSame($userName, $channelFollow->getUserName());

        $this->assertSame(
            [
                'followed_at' => $followDateTime->format(DATE_RFC3339_EXTENDED),
                'user_id' => $userId,
                'user_login' => $userLogin,
                'user_name' => $userName,
            ],
            $channelFollow->toArray()
        );
    }
}

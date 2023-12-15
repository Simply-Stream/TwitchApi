<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UserBan;

class UserBanTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = '1001';
        $moderatorId = '1002';
        $userId = '2001';
        $createdAt = new DateTimeImmutable('2023-09-01T00:00:00Z');
        $endTime = new DateTimeImmutable('2023-09-01T01:00:00Z');

        $userBan = new UserBan($broadcasterId, $moderatorId, $userId, $createdAt, $endTime);

        $this->assertSame($broadcasterId, $userBan->getBroadcasterId());
        $this->assertSame($moderatorId, $userBan->getModeratorId());
        $this->assertSame($userId, $userBan->getUserId());
        $this->assertEquals($createdAt, $userBan->getCreatedAt());
        $this->assertEquals($endTime, $userBan->getEndTime());
    }
}

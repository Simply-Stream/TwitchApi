<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BannedUser;

class BannedUserTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = 'id123';
        $userLogin = 'user123';
        $userName = 'User 123';
        $createdAt = new DateTimeImmutable('2022-12-30');
        $expiresAt = $createdAt->add(new DateInterval('P1D'));
        $reason = 'misconduct';
        $moderatorId = 'modId123';
        $moderatorLogin = 'modLogin123';
        $moderatorName = 'Mod 123';

        $bannedUser = new BannedUser(
            $userId,
            $userLogin,
            $userName,
            $expiresAt,
            $createdAt,
            $reason,
            $moderatorId,
            $moderatorLogin,
            $moderatorName
        );

        $this->assertEquals($userId, $bannedUser->getUserId());
        $this->assertEquals($userLogin, $bannedUser->getUserLogin());
        $this->assertEquals($userName, $bannedUser->getUserName());
        $this->assertEquals($expiresAt, $bannedUser->getExpiresAt());
        $this->assertEquals($createdAt, $bannedUser->getCreatedAt());
        $this->assertEquals($reason, $bannedUser->getReason());
        $this->assertEquals($moderatorId, $bannedUser->getModeratorId());
        $this->assertEquals($moderatorLogin, $bannedUser->getModeratorLogin());
        $this->assertEquals($moderatorName, $bannedUser->getModeratorName());
    }
}

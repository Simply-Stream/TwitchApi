<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\Moderator;

class ModeratorTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = 'user123';
        $userLogin = 'user_login';
        $userName = 'User Name';

        $moderator = new Moderator($userId, $userLogin, $userName);

        $this->assertInstanceOf(Moderator::class, $moderator);

        $this->assertEquals($userId, $moderator->getUserId());
        $this->assertEquals($userLogin, $moderator->getUserLogin());
        $this->assertEquals($userName, $moderator->getUserName());
    }
}

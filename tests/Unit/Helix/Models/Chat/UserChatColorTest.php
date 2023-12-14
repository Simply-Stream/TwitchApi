<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\UserChatColor;

final class UserChatColorTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = '12345678';
        $userLogin = 'username';
        $userName = 'Username';
        $color = '#000000';

        $userChatColor = new UserChatColor($userId, $userLogin, $userName, $color);

        $this->assertInstanceOf(UserChatColor::class, $userChatColor);
        $this->assertEquals($userId, $userChatColor->getUserId());
        $this->assertEquals($userLogin, $userChatColor->getUserLogin());
        $this->assertEquals($userName, $userChatColor->getUserName());
        $this->assertEquals($color, $userChatColor->getColor());
    }
}

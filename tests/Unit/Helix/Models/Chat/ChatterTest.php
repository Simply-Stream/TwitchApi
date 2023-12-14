<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\Chatter;

final class ChatterTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = "TestUserId";
        $userLogin = "TestUserLogin";
        $userName = "TestUserName";

        $chatter = new Chatter($userId, $userLogin, $userName);

        $this->assertSame($userId, $chatter->getUserId());
        $this->assertSame($userLogin, $chatter->getUserLogin());
        $this->assertSame($userName, $chatter->getUserName());
    }
}

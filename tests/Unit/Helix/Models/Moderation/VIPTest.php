<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\VIP;

class VIPTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = 'user-id';
        $userLogin = 'user-login';
        $userName = 'user-name';

        $vip = new VIP($userId, $userLogin, $userName);

        $this->assertSame($userId, $vip->getUserId());
        $this->assertSame($userLogin, $vip->getUserLogin());
        $this->assertSame($userName, $vip->getUserName());
    }
}

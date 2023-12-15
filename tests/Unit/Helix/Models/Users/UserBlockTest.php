<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\UserBlock;

class UserBlockTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = 'testUserId';
        $userLogin = 'testUserLogin';
        $displayName = 'testDisplayName';

        $userBlock = new UserBlock($userId, $userLogin, $displayName);

        $this->assertSame($userId, $userBlock->getUserId());
        $this->assertSame($userLogin, $userBlock->getUserLogin());
        $this->assertSame($displayName, $userBlock->getDisplayName());
    }
}

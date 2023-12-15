<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Teams;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Teams\Member;

class MemberTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = 'testId';
        $username = 'testUser';
        $userLogin = 'testLogin';

        $member = new Member($userId, $username, $userLogin);

        $this->assertSame($userId, $member->getUserId(), 'The UserId does not match the input.');
        $this->assertSame($username, $member->getUserName(), 'The UserName does not match the input.');
        $this->assertSame($userLogin, $member->getUserLogin(), 'The UserLogin does not match the input.');
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Bits;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Bits\BitsLeaderboard;

final class BitsLeaderboardTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $bitsLeaderboard = new BitsLeaderboard(
            $userId = "123",
            $userLogin = "userLoginTest",
            $userName = "userNameTest",
            $rank = 1,
            $score = 100
        );

        $this->assertSame($userId, $bitsLeaderboard->getUserId());
        $this->assertSame($userLogin, $bitsLeaderboard->getUserLogin());
        $this->assertSame($userName, $bitsLeaderboard->getUserName());
        $this->assertSame($rank, $bitsLeaderboard->getRank());
        $this->assertSame($score, $bitsLeaderboard->getScore());

        $this->assertIsArray($bitsLeaderboard->toArray());
        $this->assertSame([
            'user_id' => '123',
            'user_login' => 'userLoginTest',
            'user_name' => 'userNameTest',
            'rank' => 1,
            'score' => 100,
        ], $bitsLeaderboard->toArray());
    }
}

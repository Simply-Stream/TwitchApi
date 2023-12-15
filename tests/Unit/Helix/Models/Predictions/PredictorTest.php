<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Predictions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Predictor;

final class PredictorTest extends TestCase
{
    #[DataProvider('predictorDataProvider')]
    public function testCanBeInitialized(
        string $userId,
        string $userName,
        string $userLogin,
        ?int $channelPointsUsed,
        ?int $channelPointsWon
    ) {
        $predictor = new Predictor($userId, $userName, $userLogin, $channelPointsUsed, $channelPointsWon);

        $this->assertEquals($userId, $predictor->getUserId());
        $this->assertEquals($userName, $predictor->getUserName());
        $this->assertEquals($userLogin, $predictor->getUserLogin());
        $this->assertEquals($channelPointsUsed, $predictor->getChannelPointsUsed());
        $this->assertEquals($channelPointsWon, $predictor->getChannelPointsWon());
    }

    public static function predictorDataProvider()
    {
        return [
            ['userId1', 'userName1', 'userLogin1', 100, 200],
            ['userId2', 'userName2', 'userLogin2', null, null],
            ['userId3', 'userName3', 'userLogin3', 300, null],
            ['userId4', 'userName4', 'userLogin4', null, 400],
            ['userId5', 'userName5', 'userLogin5', 500, 500],
        ];
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Predictions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Predictor;

class OutcomeTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = "outcome1";
        $title = "outcomeTitle";
        $color = "BLUE";
        $users = 42;
        $channelPoints = 1000;
        $topPredictors = [new Predictor("user1", "name1", "login1", 500, 2000)];

        $outcome = new Outcome(
            $id,
            $title,
            $color,
            $users,
            $channelPoints,
            $topPredictors
        );

        $this->assertSame($id, $outcome->getId(), "ID mismatch in Outcome class.");
        $this->assertSame($title, $outcome->getTitle(), "Title mismatch in Outcome class.");
        $this->assertSame($color, $outcome->getColor(), "Color mismatch in Outcome class.");
        $this->assertSame($users, $outcome->getUsers(), "Users count mismatch in Outcome class.");
        $this->assertSame($channelPoints, $outcome->getChannelPoints(), "Channel Points mismatch in Outcome class.");
        $this->assertSame($topPredictors, $outcome->getTopPredictors(), "Top predictors mismatch in Outcome class.");
    }
}

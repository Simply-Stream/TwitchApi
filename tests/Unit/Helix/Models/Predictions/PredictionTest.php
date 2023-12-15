<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Predictions;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Outcome;
use SimplyStream\TwitchApi\Helix\Models\Predictions\Prediction;

class PredictionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $outcomes = [
            new Outcome(
                'id1',
                'outcome1',
                'red',
                10,
                100,
                ['predictor1', 'predictor2']
            ),
            new Outcome(
                'id2',
                'outcome2',
                'blue',
                20,
                200,
                ['predictor3', 'predictor4']
            ),
        ];

        $prediction = new Prediction(
            'predictionId',
            'broadcasterId',
            'broadcasterName',
            'broadcasterLogin',
            'Will I finish this entire pizza?',
            $outcomes,
            300,
            'ACTIVE',
            new DateTimeImmutable(),
            'winningOutcomeId',
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );

        $this->assertSame('predictionId', $prediction->getId());
        $this->assertSame('broadcasterId', $prediction->getBroadcasterId());
        $this->assertSame('broadcasterName', $prediction->getBroadcasterName());
        $this->assertSame('broadcasterLogin', $prediction->getBroadcasterLogin());
        $this->assertSame('Will I finish this entire pizza?', $prediction->getTitle());
        $this->assertSame($outcomes, $prediction->getOutcomes());
        $this->assertSame(300, $prediction->getPredictionWindow());
        $this->assertSame('ACTIVE', $prediction->getStatus());
        $this->assertSame('winningOutcomeId', $prediction->getWinningOutcomeId());
    }
}

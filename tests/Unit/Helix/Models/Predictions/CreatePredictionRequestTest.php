<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Predictions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Predictions\CreatePredictionRequest;

final class CreatePredictionRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = 'broadcaster123';
        $title = 'Will I finish this entire pizza?';
        $outcomes = [['title' => 'Yes'], ['title' => 'No']];
        $predictionWindow = 180;

        $createPredictionRequest = new CreatePredictionRequest(
            $broadcasterId,
            $title,
            $outcomes,
            $predictionWindow
        );

        $this->assertEquals($broadcasterId, $createPredictionRequest->getBroadcasterId());
        $this->assertEquals($title, $createPredictionRequest->getTitle());
        $this->assertEquals($outcomes, $createPredictionRequest->getOutcomes());
        $this->assertEquals($predictionWindow, $createPredictionRequest->getPredictionWindow());
    }
}

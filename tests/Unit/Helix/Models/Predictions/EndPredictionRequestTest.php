<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Predictions;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Predictions\EndPredictionRequest;

/**
 * The class EndPredictionRequestTest includes tests for testing
 * the __construct method of SimplyStream\TwitchApi\Helix\Models\Predictions\EndPredictionRequest class.
 */
class EndPredictionRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $EndPredictionRequest = new EndPredictionRequest(
            'broadcasterId123',
            'predictionId123',
            'RESOLVED',
            'outcomeId123'
        );
        $this->assertInstanceOf(EndPredictionRequest::class, $EndPredictionRequest);
        $this->assertEquals('broadcasterId123', $EndPredictionRequest->getBroadcasterId());
        $this->assertEquals('predictionId123', $EndPredictionRequest->getId());
        $this->assertEquals('RESOLVED', $EndPredictionRequest->getStatus());
        $this->assertEquals('outcomeId123', $EndPredictionRequest->getWinningOutcomeId());
    }

    public function testConstructWithEmptyPredictionId()
    {
        $this->expectException(InvalidArgumentException::class);
        new EndPredictionRequest('broadcasterId123', '', 'RESOLVED', 'outcomeId123');
    }

    public function testConstructWithInvalidStatus()
    {
        $this->expectException(InvalidArgumentException::class);
        new EndPredictionRequest('broadcasterId123', 'predictionId123', 'INVALID_STATUS', 'outcomeId123');
    }

    public function testConstructWithResolvedStatusAndEmptyOutcomeId()
    {
        $this->expectException(InvalidArgumentException::class);
        new EndPredictionRequest('broadcasterId123', 'predictionId123', 'RESOLVED', '');
    }
}

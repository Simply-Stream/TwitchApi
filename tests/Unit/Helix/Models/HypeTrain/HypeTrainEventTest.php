<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\HypeTrain;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\Contribution;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\EventData;
use SimplyStream\TwitchApi\Helix\Models\HypeTrain\HypeTrainEvent;

class HypeTrainEventTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $exampleId = '1234';
        $exampleEventType = 'hypetrain.progression';
        $exampleEventTimestamp = new DateTimeImmutable();
        $exampleVersion = '1';
        $exampleEventData = new EventData(
            'broadcasterId',
            new DateTimeImmutable(),
            new DateTimeImmutable(),
            10,
            'id',
            new Contribution(1, 'BITS', '1234'),
            1,
            new DateTimeImmutable(),
            [],
            150
        );

        $hypeTrainEvent = new HypeTrainEvent(
            $exampleId,
            $exampleEventType,
            $exampleEventTimestamp,
            $exampleVersion,
            $exampleEventData
        );

        $this->assertEquals($exampleId, $hypeTrainEvent->getId());
        $this->assertEquals($exampleEventType, $hypeTrainEvent->getEventType());
        $this->assertEquals($exampleEventTimestamp, $hypeTrainEvent->getEventTimestamp());
        $this->assertEquals($exampleVersion, $hypeTrainEvent->getVersion());
        $this->assertInstanceOf(EventData::class, $hypeTrainEvent->getEventData());
    }
}

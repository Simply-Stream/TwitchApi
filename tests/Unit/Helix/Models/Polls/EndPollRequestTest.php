<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Polls;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Polls\EndPollRequest;

class EndPollRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = '123456';
        $id = '7890';
        $status = 'TERMINATED';

        $endPollRequest = new EndPollRequest($broadcasterId, $id, $status);

        $this->assertSame($broadcasterId, $endPollRequest->getBroadcasterId());
        $this->assertSame($id, $endPollRequest->getId());
        $this->assertSame($status, $endPollRequest->getStatus());
    }

    public function testConstructWithInvalidStatus()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Status can only be one of the following values: "TERMINATED", "ARCHIVED". Got "INVALID"');

        new EndPollRequest('123456', '78910', 'INVALID');
    }

    public function testConstructWithEmptyBroadcasterId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Broadcaster ID can\'t be empty');

        new EndPollRequest('', '78910', 'ARCHIVED');
    }

    public function testConstructWithEmptyId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ID can\'t be empty');

        new EndPollRequest('12345', '', 'ARCHIVED');
    }

    public function testConstructWithEmptyStatus()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Status can\'t be empty');

        new EndPollRequest('12345', '78910', '');
    }
}

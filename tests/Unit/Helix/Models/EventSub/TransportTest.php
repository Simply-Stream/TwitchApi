<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\EventSub;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\EventSub\Transport;

class TransportTest extends TestCase
{
    public function testConstructWebhook()
    {
        $transport = new Transport('webhook', 'https://example.com/callback', 'secret_code');

        $this->assertInstanceOf(Transport::class, $transport);
        $this->assertEquals('webhook', $transport->getMethod());
        $this->assertEquals('https://example.com/callback', $transport->getCallback());
        $this->assertEquals('secret_code', $transport->getSecret());
        $this->assertNull($transport->getSessionId());
    }

    public function testConstructWebsocket()
    {
        $transport = new Transport('websocket', null, null, 'session_id');

        $this->assertInstanceOf(Transport::class, $transport);
        $this->assertEquals('websocket', $transport->getMethod());
        $this->assertNull($transport->getCallback());
        $this->assertNull($transport->getSecret());
        $this->assertEquals('session_id', $transport->getSessionId());
    }

    public function testConstructInvalidMethod()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected one of: "webhook", "websocket". Got: "invalid_method"');

        new Transport('invalid_method');
    }
}

<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Whispers;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Whispers\SendWhisperRequest;

final class SendWhisperRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $testMessage = 'Test message!';

        $sendWhisperRequest = new SendWhisperRequest($testMessage);

        $this->assertSame($testMessage, $sendWhisperRequest->getMessage());
    }
}

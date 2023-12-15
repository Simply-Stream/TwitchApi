<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\CheckAutoModStatus;

final class CheckAutoModStatusTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $msgId = 'testId';
        $msgText = 'testMessage';

        $checkAutoModStatus = new CheckAutoModStatus($msgId, $msgText);

        $this->assertInstanceOf(CheckAutoModStatus::class, $checkAutoModStatus);
        $this->assertEquals($msgId, $checkAutoModStatus->getMsgId());
        $this->assertEquals($msgText, $checkAutoModStatus->getMsgText());
    }
}

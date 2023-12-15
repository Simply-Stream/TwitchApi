<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AutoModStatus;

final class AutoModStatusTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $msgId = "12345";
        $isPermitted = true;

        $autoModStatus = new AutoModStatus($msgId, $isPermitted);

        $this->assertEquals($msgId, $autoModStatus->getMsgId());
        $this->assertEquals($isPermitted, $autoModStatus->isPermitted());
    }
}

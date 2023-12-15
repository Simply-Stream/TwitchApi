<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ShieldModeStatus;

final class ShieldModeStatusTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $isActive = true;
        $moderatorId = 'testModeratorId';
        $moderatorLogin = 'testModeratorLogin';
        $moderatorName = 'testModeratorName';
        $lastActivatedAt = new DateTimeImmutable();

        $shieldModeStatus = new ShieldModeStatus(
            $isActive,
            $moderatorId,
            $moderatorLogin,
            $moderatorName,
            $lastActivatedAt
        );

        $this->assertEquals($isActive, $shieldModeStatus->isActive());
        $this->assertEquals($moderatorId, $shieldModeStatus->getModeratorId());
        $this->assertEquals($moderatorLogin, $shieldModeStatus->getModeratorLogin());
        $this->assertEquals($moderatorName, $shieldModeStatus->getModeratorName());
        $this->assertEquals($lastActivatedAt, $shieldModeStatus->getLastActivatedAt());
    }
}

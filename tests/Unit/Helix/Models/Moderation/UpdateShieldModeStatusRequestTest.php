<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\UpdateShieldModeStatusRequest;

final class UpdateShieldModeStatusRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $isActive = true;

        $request = new UpdateShieldModeStatusRequest($isActive);

        $this->assertEquals($isActive, $request->isActive());
    }
}

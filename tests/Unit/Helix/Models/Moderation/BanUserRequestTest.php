<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BanUser;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BanUserRequest;

final class BanUserRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $banUser = new BanUser('12345', 60, 'Just because');
        $banUserRequest = new BanUserRequest($banUser);

        $this->assertSame($banUser, $banUserRequest->getData());
    }
}

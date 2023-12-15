<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Users;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Users\UpdateUserChatColorRequest;

final class UpdateUserChatColorRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $userId = '123456';
        $color = 'blue';

        $updateUserChatColorRequest = new UpdateUserChatColorRequest($userId, $color);

        $this->assertSame($userId, $updateUserChatColorRequest->getUserId());
        $this->assertSame($color, $updateUserChatColorRequest->getColor());
    }
}

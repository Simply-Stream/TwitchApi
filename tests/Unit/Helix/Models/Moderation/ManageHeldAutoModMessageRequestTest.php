<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\ManageHeldAutoModMessageRequest;

class ManageHeldAutoModMessageRequestTest extends TestCase
{
    public static function validDataProvider()
    {
        return [
            ['1234', '5678', 'ALLOW'],
            ['4321', '8765', 'DENY'],
        ];
    }

    public static function invalidDataProvider()
    {
        return [
            ['1234', '5678', 'INVALID_ACTION'],
            ['4321', '8765', 'ALLOW_DENY'],
        ];
    }

    #[DataProvider('validDataProvider')]
    public function testCanBeInitialized(string $userId, string $msgId, string $action)
    {
        $manageHeldAutoModMessageRequest = new ManageHeldAutoModMessageRequest($userId, $msgId, $action);

        $this->assertSame($userId, $manageHeldAutoModMessageRequest->getUserId());
        $this->assertSame($msgId, $manageHeldAutoModMessageRequest->getMsgId());
        $this->assertSame($action, $manageHeldAutoModMessageRequest->getAction());
    }

    #[DataProvider('invalidDataProvider')]
    public function testCanBeInitializedInvalidAction(string $userId, string $msgId, string $action)
    {
        $this->expectException(InvalidArgumentException::class);

        new ManageHeldAutoModMessageRequest($userId, $msgId, $action);
    }
}

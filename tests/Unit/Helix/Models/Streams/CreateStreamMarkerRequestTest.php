<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Streams;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Streams\CreateStreamMarkerRequest;

class CreateStreamMarkerRequestTest extends TestCase
{
    #[DataProvider('canBeInitializedProvider')]
    public function testCanBeInitialized(
        string $userId,
        string $description,
        string $expectedUserId,
        string $expectedDescription
    ) {
        $createStreamMarkerRequest = new CreateStreamMarkerRequest($userId, $description);
        $this->assertEquals($expectedUserId, $createStreamMarkerRequest->getUserId());
        $this->assertEquals($expectedDescription, $createStreamMarkerRequest->getDescription());
    }

    public static function canBeInitializedProvider()
    {
        return [
            ['user123', 'description', 'user123', 'description'],
            ['user456', 'another description', 'user456', 'another description'],
        ];
    }

    public function testConstructDescriptionTooLong()
    {
        $this->expectException(\InvalidArgumentException::class);
        $description = str_repeat('a', 141);
        new CreateStreamMarkerRequest('user789', $description);
    }
}

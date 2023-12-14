<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Channels;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelEditor;

final class ChannelEditorTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $userId = 'testUserId';
        $username = 'testUsername';
        $createdAt = new DateTimeImmutable();

        $channelEditor = new ChannelEditor($userId, $username, $createdAt);

        $this->assertSame($userId, $channelEditor->getUserId());
        $this->assertSame($username, $channelEditor->getUserName());
        $this->assertSame($createdAt, $channelEditor->getCreatedAt());

        $this->assertIsArray($channelEditor->toArray());
        $this->assertSame([
            'user_id' => $userId,
            'user_name' => $username,
            'created_at' => $createdAt->format(DATE_RFC3339_EXTENDED),
        ], $channelEditor->toArray());
    }
}

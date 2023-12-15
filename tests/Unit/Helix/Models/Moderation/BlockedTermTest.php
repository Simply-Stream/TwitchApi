<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\BlockedTerm;

final class BlockedTermTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = '12345';
        $moderatorId = '54321';
        $id = 'blocked1';
        $text = 'badword';
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();
        $expiresAt = new DateTimeImmutable();

        $blockedTerm = new BlockedTerm(
            $broadcasterId,
            $moderatorId,
            $id,
            $text,
            $createdAt,
            $updatedAt,
            $expiresAt
        );

        $this->assertEquals($broadcasterId, $blockedTerm->getBroadcasterId());
        $this->assertEquals($moderatorId, $blockedTerm->getModeratorId());
        $this->assertEquals($id, $blockedTerm->getId());
        $this->assertEquals($text, $blockedTerm->getText());
        $this->assertEquals($createdAt, $blockedTerm->getCreatedAt());
        $this->assertEquals($updatedAt, $blockedTerm->getUpdatedAt());
        $this->assertEquals($expiresAt, $blockedTerm->getExpiresAt());
    }
}

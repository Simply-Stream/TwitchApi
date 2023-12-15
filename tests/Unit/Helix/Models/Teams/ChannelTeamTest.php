<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Teams;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Teams\ChannelTeam;

final class ChannelTeamTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = "123456";
        $broadcasterName = "Broadcaster Name";
        $broadcasterLogin = "Broadcaster Login";
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();
        $info = "Team Info";
        $thumbnailUrl = "http://example.com/thumbnail.png";
        $teamName = "Team Name";
        $teamDisplayName = "Team Display Name";
        $id = "78910";
        $backgroundImageUrl = "http://example.com/background.png";
        $banner = "http://example.com/banner.png";

        $channelTeam = new ChannelTeam(
            $broadcasterId,
            $broadcasterName,
            $broadcasterLogin,
            $createdAt,
            $updatedAt,
            $info,
            $thumbnailUrl,
            $teamName,
            $teamDisplayName,
            $id,
            $backgroundImageUrl,
            $banner
        );

        $this->assertSame($broadcasterId, $channelTeam->getBroadcasterId());
        $this->assertSame($broadcasterName, $channelTeam->getBroadcasterName());
        $this->assertSame($broadcasterLogin, $channelTeam->getBroadcasterLogin());
        $this->assertSame($createdAt, $channelTeam->getCreatedAt());
        $this->assertSame($updatedAt, $channelTeam->getUpdatedAt());
        $this->assertSame($info, $channelTeam->getInfo());
        $this->assertSame($thumbnailUrl, $channelTeam->getThumbnailUrl());
        $this->assertSame($teamName, $channelTeam->getTeamName());
        $this->assertSame($teamDisplayName, $channelTeam->getTeamDisplayName());
        $this->assertSame($id, $channelTeam->getId());
        $this->assertSame($backgroundImageUrl, $channelTeam->getBackgroundImageUrl());
        $this->assertSame($banner, $channelTeam->getBanner());
    }
}

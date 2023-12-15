<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Teams;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Teams\Member;
use SimplyStream\TwitchApi\Helix\Models\Teams\Team;

class TeamTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $members = [
            new Member('1', 'JohnDoe', 'JohnDoe'),
        ];
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();
        $info = 'Team information goes here';
        $thumbnailUrl = 'https://example.com/thumbnail.jpg';
        $teamName = 'TeamName';
        $teamDisplayName = 'TeamDisplayName';
        $id = '1';
        $backgroundImageUrl = 'https://example.com/bgimage.jpg';
        $banner = 'https://example.com/banner.jpg';

        $team = new Team(
            $members,
            $createdAt,
            $updatedAt,
            $info,
            $thumbnailUrl,
            $teamName,
            $teamDisplayName,
            $id,
            $backgroundImageUrl,
            $banner,
        );

        $this->assertEquals($members, $team->getUsers());
        $this->assertEquals($createdAt, $team->getCreatedAt());
        $this->assertEquals($updatedAt, $team->getUpdatedAt());
        $this->assertEquals($info, $team->getInfo());
        $this->assertEquals($thumbnailUrl, $team->getThumbnailUrl());
        $this->assertEquals($teamName, $team->getTeamName());
        $this->assertEquals($teamDisplayName, $team->getTeamDisplayName());
        $this->assertEquals($id, $team->getId());
        $this->assertEquals($backgroundImageUrl, $team->getBackgroundImageUrl());
        $this->assertEquals($banner, $team->getBanner());
    }
}

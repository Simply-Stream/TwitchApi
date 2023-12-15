<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Videos;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Videos\MutedSegment;
use SimplyStream\TwitchApi\Helix\Models\Videos\Video;

class VideoTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $mutedSegments = [new MutedSegment(0, 10)];

        $video = new Video(
            '12345',
            '67890',
            '11111',
            'Streamer',
            'Streamer Name',
            'Video title',
            'Video description',
            new DateTimeImmutable('2023-03-01 06:30:00'),
            new DateTimeImmutable('2023-03-01 07:00:00'),
            'https://twitch.tv/streams/12345',
            'https://site.com/thumbnail.jpg',
            'public',
            10000,
            'EN',
            'archive',
            '3m21s',
            $mutedSegments
        );

        $this->assertEquals('12345', $video->getId());
        $this->assertEquals('67890', $video->getStreamId());
        $this->assertEquals('11111', $video->getUserId());
        $this->assertEquals('Streamer', $video->getUserLogin());
        $this->assertEquals('Streamer Name', $video->getUserName());
        $this->assertEquals('Video title', $video->getTitle());
        $this->assertEquals('Video description', $video->getDescription());
        $this->assertEquals(new DateTimeImmutable('2023-03-01 06:30:00'), $video->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('2023-03-01 07:00:00'), $video->getPublishedAt());
        $this->assertEquals('https://twitch.tv/streams/12345', $video->getUrl());
        $this->assertEquals('https://site.com/thumbnail.jpg', $video->getThumbnailUrl());
        $this->assertEquals('public', $video->getViewable());
        $this->assertEquals(10000, $video->getViewCount());
        $this->assertEquals('EN', $video->getLanguage());
        $this->assertEquals('archive', $video->getType());
        $this->assertEquals('3m21s', $video->getDuration());
        $this->assertEquals($mutedSegments, $video->getMutedSegments());
    }
}

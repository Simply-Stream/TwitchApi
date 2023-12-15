<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Streams;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Streams\Marker;
use SimplyStream\TwitchApi\Helix\Models\Streams\Video;

class VideoTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $videoId = 'sampleVideoId';
        $markerId = 'sampleMarkerId';
        $createdAt = new \DateTimeImmutable();
        $positionSeconds = 1;
        $description = 'sampleDescription';
        $url = 'https://example.com';

        $marker = new Marker($markerId, $createdAt, $positionSeconds, $description, $url);
        $video = new Video($videoId, [$marker]);

        self::assertSame($videoId, $video->getVideoId());
        self::assertCount(1, $video->getMarkers());

        $marker = $video->getMarkers()[0];
        self::assertSame($markerId, $marker->getId());
        self::assertSame($createdAt, $marker->getCreatedAt());
        self::assertSame($positionSeconds, $marker->getPositionSeconds());
        self::assertSame($description, $marker->getDescription());
        self::assertSame($url, $marker->getUrl());
    }
}

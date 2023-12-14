<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Clip;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Clip\Clip;

final class ClipTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = 'testId';
        $url = 'testUrl';
        $embedUrl = 'testEmbedUrl';
        $broadcasterId = 'testBroadcasterId';
        $broadcasterName = 'testBroadcasterName';
        $creatorId = 'testCreatorId';
        $creatorName = 'testCreatorName';
        $videoId = 'testVideoId';
        $gameId = 'testGameId';
        $language = 'en';
        $title = 'testTitle';
        $viewCount = 0;
        $createdAt = new DateTimeImmutable();
        $thumbnailUrl = 'testThumbnailUrl';
        $duration = 0.0;
        $isFeatured = false;
        $vodOffset = null;

        $clip = new Clip(
            $id,
            $url,
            $embedUrl,
            $broadcasterId,
            $broadcasterName,
            $creatorId,
            $creatorName,
            $videoId,
            $gameId,
            $language,
            $title,
            $viewCount,
            $createdAt,
            $thumbnailUrl,
            $duration,
            $isFeatured,
            $vodOffset
        );

        $this->assertSame($id, $clip->getId());
        $this->assertSame($url, $clip->getUrl());
        $this->assertSame($embedUrl, $clip->getEmbedUrl());
        $this->assertSame($broadcasterId, $clip->getBroadcasterId());
        $this->assertSame($broadcasterName, $clip->getBroadcasterName());
        $this->assertSame($creatorId, $clip->getCreatorId());
        $this->assertSame($creatorName, $clip->getCreatorName());
        $this->assertSame($videoId, $clip->getVideoId());
        $this->assertSame($gameId, $clip->getGameId());
        $this->assertSame($language, $clip->getLanguage());
        $this->assertSame($title, $clip->getTitle());
        $this->assertSame($viewCount, $clip->getViewCount());
        $this->assertSame($createdAt, $clip->getCreatedAt());
        $this->assertSame($thumbnailUrl, $clip->getThumbnailUrl());
        $this->assertSame($duration, $clip->getDuration());
        $this->assertSame($isFeatured, $clip->isFeatured());
        $this->assertSame($vodOffset, $clip->getVodOffset());
    }
}

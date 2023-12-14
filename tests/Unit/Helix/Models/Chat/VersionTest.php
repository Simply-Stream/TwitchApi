<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\Version;

final class VersionTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = 'testId';
        $imageUrl1x = 'https://example.com/image1x.png';
        $imageUrl2x = 'https://example.com/image2x.png';
        $imageUrl4x = 'https://example.com/image4x.png';
        $title = 'testTitle';
        $description = 'testDescription';
        $clickAction = 'testClickAction';
        $clickUrl = 'https://example.com';

        $version = new Version(
            $id,
            $imageUrl1x,
            $imageUrl2x,
            $imageUrl4x,
            $title,
            $description,
            $clickAction,
            $clickUrl
        );

        $this->assertEquals($id, $version->getId());
        $this->assertEquals($imageUrl1x, $version->getImageUrl1x());
        $this->assertEquals($imageUrl2x, $version->getImageUrl2x());
        $this->assertEquals($imageUrl4x, $version->getImageUrl4x());
        $this->assertEquals($title, $version->getTitle());
        $this->assertEquals($description, $version->getDescription());
        $this->assertEquals($clickAction, $version->getClickAction());
        $this->assertEquals($clickUrl, $version->getClickUrl());

        $version = new Version($id, $imageUrl1x, $imageUrl2x, $imageUrl4x, $title, $description);

        $this->assertNull($version->getClickAction());
        $this->assertNull($version->getClickUrl());
    }
}

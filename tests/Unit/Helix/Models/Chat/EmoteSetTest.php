<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\EmoteSet;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;

final class EmoteSetTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $images = new Image('url1x', 'url2x', 'url4x');
        $format = [];
        $scale = [];
        $themeMode = [];

        $emoteSet = new EmoteSet(
            'id',
            'name',
            $images,
            $format,
            $scale,
            $themeMode,
            'bitstier',
            'emoteSetId',
            'ownerId'
        );

        $this->assertSame('id', $emoteSet->getId());
        $this->assertSame('name', $emoteSet->getName());
        $this->assertSame($images, $emoteSet->getImages());
        $this->assertSame($format, $emoteSet->getFormat());
        $this->assertSame($scale, $emoteSet->getScale());
        $this->assertSame($themeMode, $emoteSet->getThemeMode());
        $this->assertSame('bitstier', $emoteSet->getEmoteType());
        $this->assertSame('emoteSetId', $emoteSet->getEmoteSetId());
        $this->assertSame('ownerId', $emoteSet->getOwnerId());
    }
}

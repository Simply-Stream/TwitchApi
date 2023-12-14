<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\Emote;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;

final class EmoteTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = '1234';
        $name = 'testEmote';
        $images = new Image('url1', 'url2', 'url3');
        $format = ['animated', 'static'];
        $scale = ['1.0', '2.0', '3.0'];
        $themeMode = ['light', 'dark'];

        $emote = new Emote($id, $name, $images, $format, $scale, $themeMode);

        $this->assertInstanceOf(Emote::class, $emote);
        $this->assertEquals($id, $emote->getId());
        $this->assertEquals($name, $emote->getName());
        $this->assertEquals($images, $emote->getImages());
        $this->assertEquals($format, $emote->getFormat());
        $this->assertEquals($scale, $emote->getScale());
        $this->assertEquals($themeMode, $emote->getThemeMode());
    }
}

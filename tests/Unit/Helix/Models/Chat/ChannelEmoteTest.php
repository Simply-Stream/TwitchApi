<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\ChannelEmote;
use SimplyStream\TwitchApi\Helix\Models\Chat\Emote;
use SimplyStream\TwitchApi\Helix\Models\Chat\Image;

final class ChannelEmoteTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $id = 'testId';
        $name = 'testName';
        $images = new Image('url1x', 'url2x', 'url4x');
        $format = [
            'type' => 'typeTest',
            'url' => 'urlTest'
        ];
        $scale = [1, 2];
        $themeMode = ['dark'];
        $tier = 'testTier';
        $emoteType = 'subscriptions';
        $emoteSetId = 'testEmoteSetId';

        $channelEmote = new ChannelEmote($id, $name, $images, $format, $scale, $themeMode, $tier, $emoteType, $emoteSetId);

        $this->assertInstanceOf(ChannelEmote::class, $channelEmote);
        $this->assertInstanceOf(Emote::class, $channelEmote);
        $this->assertEquals($id, $channelEmote->getId());
        $this->assertEquals($name, $channelEmote->getName());
        $this->assertInstanceOf(Image::class, $channelEmote->getImages());
        $this->assertEquals($tier, $channelEmote->getTier());
        $this->assertEquals($emoteType, $channelEmote->getEmoteType());
        $this->assertEquals($emoteSetId, $channelEmote->getEmoteSetId());

        $expectedResult = [
            'id' => $id,
            'name' => $name,
            'images' => $images->toArray(),
            'format' => $format,
            'scale' => $scale,
            'theme_mode' => $themeMode,
            'tier' => $tier,
            'emote_type' => $emoteType,
            'emote_set_id' => $emoteSetId
        ];
        $this->assertEquals($expectedResult, $channelEmote->toArray());
    }
}

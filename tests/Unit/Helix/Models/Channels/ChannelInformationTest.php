<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Channels;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Channels\ChannelInformation;

final class ChannelInformationTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $broadcasterId = '1234';
        $broadcasterLogin = 'broadcasterLogin';
        $broadcasterName = 'broadcasterName';
        $broadcasterLanguage = 'en';
        $gameName = 'gameName';
        $gameId = 'gameId';
        $title = 'title';
        $delay = 30;
        $tags = ['tag1', 'tag2'];
        $contentClassifLabels = ['label1', 'label2'];
        $isBrandedContent = true;

        $channelInformation = new ChannelInformation(
            $broadcasterId,
            $broadcasterLogin,
            $broadcasterName,
            $broadcasterLanguage,
            $gameName,
            $gameId,
            $title,
            $delay,
            $tags,
            $contentClassifLabels,
            $isBrandedContent
        );

        $this->assertSame($broadcasterId, $channelInformation->getBroadcasterId());
        $this->assertSame($broadcasterLogin, $channelInformation->getBroadcasterLogin());
        $this->assertSame($broadcasterName, $channelInformation->getBroadcasterName());
        $this->assertSame($broadcasterLanguage, $channelInformation->getBroadcasterLanguage());
        $this->assertSame($gameName, $channelInformation->getGameName());
        $this->assertSame($gameId, $channelInformation->getGameId());
        $this->assertSame($title, $channelInformation->getTitle());
        $this->assertSame($delay, $channelInformation->getDelay());
        $this->assertSame($tags, $channelInformation->getTags());
        $this->assertSame($contentClassifLabels, $channelInformation->getContentClassificationLabels());
        $this->assertSame($isBrandedContent, $channelInformation->isBrandedContent());

        $expectedArray = [
            'broadcaster_id' => $broadcasterId,
            'broadcaster_login' => $broadcasterLogin,
            'broadcaster_name' => $broadcasterName,
            'broadcaster_language' => $broadcasterLanguage,
            'game_name' => $gameName,
            'game_id' => $gameId,
            'title' => $title,
            'delay' => $delay,
            'tags' => $tags,
            'content_classification_labels' => $contentClassifLabels,
            'is_branded_content' => $isBrandedContent,
        ];

        $this->assertIsArray($channelInformation->toArray());
        $this->assertSame($expectedArray, $channelInformation->toArray());
    }
}

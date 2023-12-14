<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Channels;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Channels\Label;
use SimplyStream\TwitchApi\Helix\Models\Channels\ModifyChannelInformationRequest;
use Webmozart\Assert\InvalidArgumentException;

use function PHPUnit\Framework\assertIsArray;

final class ModifyChannelInformationRequestTest extends TestCase
{
    public function testCanBeInitialized(): void
    {
        $labels = [
            new Label('label1', true),
            new Label('label2', false),
        ];

        $tags = ['tag1', 'tag2', 'tag3'];

        $request = new ModifyChannelInformationRequest(
            'gameId',
            'en',
            'Stream title',
            500,
            $tags,
            $labels,
            true
        );

        self::assertSame('gameId', $request->getGameId());
        self::assertSame('en', $request->getBroadcasterLanguage());
        self::assertSame('Stream title', $request->getTitle());
        self::assertSame(500, $request->getDelay());
        self::assertSame($tags, $request->getTags());
        self::assertSame($labels, $request->getContentClassificationLabels());
        self::assertSame(true, $request->getIsBrandedContent());

        assertIsArray($request->toArray());
        $expectedArray = [
            'game_id' => 'gameId',
            'broadcaster_language' => 'en',
            'title' => 'Stream title',
            'delay' => 500,
            'tags' => $tags,
            'content_classification_labels' => array_map(function ($label) {
                return $label->toArray();
            }, $labels),
            'is_branded_content' => true,
        ];

        self::assertEquals($expectedArray, $request->toArray());
    }

    public function testThrowsExceptionForInvalidBroadcasterLanguage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value to contain 2 characters. Got: "english"');

        new ModifyChannelInformationRequest(null, 'english');
    }

    public function testThrowsExceptionForInvalidTitle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a different value than "".');

        new ModifyChannelInformationRequest(null, null, '');
    }

    public function testThrowsExceptionForInvalidDelay(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The maximum delay is 900 seconds');

        new ModifyChannelInformationRequest(null, null, null, 1000);
    }

    public function testThrowsExceptionForInvalidTags(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each tag is limited to a maximum of 25 characters');
        new ModifyChannelInformationRequest(
            null,
            null,
            null,
            null,
            ['It\'s a long long tag! It\'s a long long tag! It\'s a long long tag!']
        );
    }

    public function testThrowsExceptionForInvalidContentClassificationLabel(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Content classification labels need to be an instance of "SimplyStream\TwitchApi\Helix\Models\Channels\Label"');

        new ModifyChannelInformationRequest(null, null, null, null, null, ['invalid']);
    }
}

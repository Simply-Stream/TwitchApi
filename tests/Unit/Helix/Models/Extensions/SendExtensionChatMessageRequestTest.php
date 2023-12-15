<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SendExtensionChatMessageRequest;
use Webmozart\Assert\InvalidArgumentException;

final class SendExtensionChatMessageRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $text = str_repeat('a', 280);
        $id = 'test-id';
        $version = '1.0.0';
        $request = new SendExtensionChatMessageRequest($text, $id, $version);

        $this->assertSame($text, $request->getText());
        $this->assertSame($id, $request->getExtensionId());
        $this->assertSame($version, $request->getExtensionVersion());
    }

    public function testTextToLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The message may contain a maximum of 280 characters, got 281');

        $id = 'test-id';
        $version = '1.0.0';
        $longText = str_repeat('a', 281);

        new SendExtensionChatMessageRequest($longText, $id, $version);
    }
}

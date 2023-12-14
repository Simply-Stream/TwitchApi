<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Chat;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Chat\SendChatAnnouncementRequest;
use Webmozart\Assert\InvalidArgumentException;

final class SendChatAnnouncementRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $message = 'Announcement test';
        $color = 'blue';
        $instance = new SendChatAnnouncementRequest($message, $color);

        $this->assertSame($message, $instance->getMessage());
        $this->assertSame($color, $instance->getColor());
    }

    public function testConstructWithValidParameters()
    {
        $instance = new SendChatAnnouncementRequest('Announcement test', 'blue');
        $this->assertInstanceOf(SendChatAnnouncementRequest::class, $instance);
    }

    public function testConstructWithDefaultColor()
    {
        $instance = new SendChatAnnouncementRequest('Announcement test');
        $this->assertInstanceOf(SendChatAnnouncementRequest::class, $instance);
    }

    public function testConstructWithMessageLengthLimit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Messages can only be 500 characters long. Got "501" characters');

        $longMessage = str_repeat('a', 501);
        new SendChatAnnouncementRequest($longMessage);
    }

    public function testConstructWithColorRestrictions()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Color can only be one of the following values: blue, green, orange, purple, primary. Got "red"');

        $invalidColor = 'red';
        new SendChatAnnouncementRequest('Announcement test', $invalidColor);
    }
}

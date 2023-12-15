<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Moderation;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Moderation\AddBlockedTermRequest;
use Webmozart\Assert\InvalidArgumentException;

class AddBlockedTermRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $blockedTerm = new AddBlockedTermRequest('foobar');

        $this->assertSame('foobar', $blockedTerm->getText());
    }

    public function testConstructThrowsExceptionForShortTerm()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The term must contain a minimum of 2 characters, 1');

        new AddBlockedTermRequest('a');
    }

    public function testConstructThrowsExceptionForLongTerm()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The term must contain a maximum of 500 characters, 501');

        $longTerm = str_repeat('a', 501);
        new AddBlockedTermRequest($longTerm);
    }
}

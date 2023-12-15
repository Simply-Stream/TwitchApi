<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\Secret;

class SecretTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $secretContent = 'testSecret';
        $activeAt = new DateTimeImmutable();
        $expiresAt = new DateTimeImmutable();
        $secret = new Secret($secretContent, $activeAt, $expiresAt);

        $this->assertSame($secretContent, $secret->getContent());
        $this->assertSame($activeAt, $secret->getActiveAt());
        $this->assertSame($expiresAt, $secret->getExpiresAt());
    }
}

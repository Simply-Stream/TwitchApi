<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\ExtensionSecret;
use SimplyStream\TwitchApi\Helix\Models\Extensions\Secret;

final class ExtensionSecretTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $dateNow = new DateTimeImmutable();
        $secrets = [
            new Secret("content1", $dateNow, $dateNow),
            new Secret("content2", $dateNow, $dateNow),
        ];

        $extensionSecret = new ExtensionSecret(1, $secrets);

        $this->assertEquals(1, $extensionSecret->getFormatVersion());
        $this->assertSame($secrets, $extensionSecret->getSecrets());
    }
}

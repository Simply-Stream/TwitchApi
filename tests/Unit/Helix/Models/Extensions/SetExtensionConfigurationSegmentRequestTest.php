<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SetExtensionConfigurationSegmentRequest;
use Webmozart\Assert\InvalidArgumentException;

final class SetExtensionConfigurationSegmentRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $setConfigRequest = new SetExtensionConfigurationSegmentRequest(
            'testExtensionId',
            'broadcaster',
            'testBroadcasterId',
            'testContent',
            'testVersion'
        );

        $this->assertSame('testExtensionId', $setConfigRequest->getExtensionId());
        $this->assertSame('broadcaster', $setConfigRequest->getSegment());
        $this->assertSame('testBroadcasterId', $setConfigRequest->getBroadcasterId());
        $this->assertSame('testContent', $setConfigRequest->getContent());
        $this->assertSame('testVersion', $setConfigRequest->getVersion());
    }

    public function testCanBeInitializedWithEmptyExtensionId()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Extension id can't be empty");

        new SetExtensionConfigurationSegmentRequest(
            '',
            'developer',
            null,
            null,
            null
        );
    }

    public function testCanBeInitializedWithInvalidSegment()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Segment got an invalid value. Allowed values: "broadcaster", "developer", "global", got "invalidSegment"');

        new SetExtensionConfigurationSegmentRequest(
            'testExtensionId',
            'invalidSegment',
            null,
            null,
            null
        );
    }
}

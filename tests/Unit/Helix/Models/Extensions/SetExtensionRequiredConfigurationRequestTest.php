<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Tests\Unit\Helix\Models\Extensions;

use PHPUnit\Framework\TestCase;
use SimplyStream\TwitchApi\Helix\Models\Extensions\SetExtensionRequiredConfigurationRequest;

final class SetExtensionRequiredConfigurationRequestTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $extensionId = 'extensionId';
        $extensionVersion = 'extensionVersion';
        $requiredConfiguration = 'requiredConfiguration';

        $setExtensionRequiredConfigurationRequest = new SetExtensionRequiredConfigurationRequest(
            $extensionId,
            $extensionVersion,
            $requiredConfiguration
        );

        $this->assertEquals($extensionId, $setExtensionRequiredConfigurationRequest->getExtensionId());
        $this->assertEquals($extensionVersion, $setExtensionRequiredConfigurationRequest->getExtensionVersion());
        $this->assertEquals($requiredConfiguration, $setExtensionRequiredConfigurationRequest->getRequiredConfiguration());
    }
}

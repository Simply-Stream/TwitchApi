<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

use Webmozart\Assert\Assert;

final readonly class SetExtensionRequiredConfigurationRequest
{
    /**
     * @param string $broadcasterId
     * @param string $extensionId           The ID of the extension to update.
     * @param string $extensionVersion      The version of the extension to update.
     * @param string $requiredConfiguration The required_configuration string to use with the extension.
     */
    public function __construct(
        public string $broadcasterId,
        public string $extensionId,
        public string $extensionVersion,
        public string $requiredConfiguration
    ) {
        Assert::stringNotEmpty($this->broadcasterId, 'Broadcaster ID can\'t be empty');
        Assert::stringNotEmpty($this->extensionId, 'Extension ID can\'t be empty');
        Assert::stringNotEmpty($this->extensionVersion, 'Extension version can\'t be empty');
        Assert::stringNotEmpty($this->requiredConfiguration, 'Required configuration can\'t be empty');
    }
}

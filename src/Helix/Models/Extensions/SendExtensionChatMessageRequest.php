<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Extensions;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class SendExtensionChatMessageRequest extends AbstractModel
{
    /**
     * @param string $text             The message. The message may contain a maximum of 280 characters.
     * @param string $extensionId      The ID of the extension that’s sending the chat message.
     * @param string $extensionVersion The extension’s version number.
     */
    public function __construct(
        private string $text,
        private string $extensionId,
        private string $extensionVersion
    ) {
        Assert::maxLength($this->text, 280, 'The message may contain a maximum of 280 characters');
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getExtensionId(): string
    {
        return $this->extensionId;
    }

    public function getExtensionVersion(): string
    {
        return $this->extensionVersion;
    }
}

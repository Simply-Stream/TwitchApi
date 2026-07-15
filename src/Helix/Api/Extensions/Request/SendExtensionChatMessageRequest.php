<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Api\Extensions\Request;

use Webmozart\Assert\Assert;

final readonly class SendExtensionChatMessageRequest
{
    /**
     * @param string $broadcasterId
     * @param string $text             The message. The message may contain a maximum of 280 characters.
     * @param string $extensionId      The ID of the extension that’s sending the chat message.
     * @param string $extensionVersion The extension’s version number.
     */
    public function __construct(
        public string $broadcasterId,
        public string $text,
        public string $extensionId,
        public string $extensionVersion
    ) {
        Assert::maxLength(
            $this->text,
            280,
            sprintf('The message may contain a maximum of %2$s characters, got %s', strlen($this->text), 280)
        );
    }
}

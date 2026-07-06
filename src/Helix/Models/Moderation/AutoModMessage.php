<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use Webmozart\Assert\Assert;

final readonly class AutoModMessage
{
    /**
     * @param string $msgId   A caller-defined ID used to correlate this message with the same message in the response.
     * @param string $msgText The message to check.
     */
    public function __construct(
        public string $msgId,
        public string $msgText,
    ) {
        Assert::stringNotEmpty($msgId, 'msgId can\'t be empty.');
        Assert::stringNotEmpty($msgText, 'msgText can\'t be empty.');
    }
}

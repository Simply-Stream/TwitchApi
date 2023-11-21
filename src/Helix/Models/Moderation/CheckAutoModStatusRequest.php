<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use SimplyStream\TwitchApi\Helix\Models\AbstractModel;

final readonly class CheckAutoModStatusRequest extends AbstractModel
{
    /**
     * @param string $msgId   A caller-defined ID used to correlate this message with the same message in the response.
     * @param string $msgText The message to check.
     */
    public function __construct(
        private string $msgId,
        private string $msgText
    ) {
    }

    public function getMsgId(): string
    {
        return $this->msgId;
    }

    public function getMsgText(): string
    {
        return $this->msgText;
    }
}

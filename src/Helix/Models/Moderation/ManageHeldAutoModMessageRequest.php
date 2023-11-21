<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApiBundle\Helix\Models\Moderation;

use SimplyStream\TwitchApiBundle\Helix\Models\AbstractModel;
use Webmozart\Assert\Assert;

final readonly class ManageHeldAutoModMessageRequest extends AbstractModel
{
    /**
     * @param string $userId The moderator who is approving or denying the held message. This ID must match the user ID
     *                       in the access token.
     * @param string $msgId  The ID of the message to allow or deny.
     * @param string $action The action to take for the message. Possible values are:
     *                       - ALLOW
     *                       - DENY
     */
    public function __construct(
        private string $userId,
        private string $msgId,
        private string $action
    ) {
        Assert::inArray($this->action, ['ALLOW', 'DENY'], 'Action has an invalid value. Allowed values: ALLOW, DENY');
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getMsgId(): string
    {
        return $this->msgId;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}

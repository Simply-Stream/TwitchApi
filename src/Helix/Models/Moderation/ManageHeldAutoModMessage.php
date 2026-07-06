<?php

declare(strict_types=1);

namespace SimplyStream\TwitchApi\Helix\Models\Moderation;

use Webmozart\Assert\Assert;

final readonly class ManageHeldAutoModMessage
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
        public string $userId,
        public string $msgId,
        public string $action
    ) {
        Assert::inArray($this->action, ['ALLOW', 'DENY'], 'Action has an invalid value. Allowed values: ALLOW, DENY');
    }
}
